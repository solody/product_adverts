<?php

namespace Drupal\product_adverts\Normalizer;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\product_adverts\Entity\ProductAdverts;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class ProductAdvertsNormalizer extends ContentEntityNormalizer {

  public function supportsNormalization($data, $format = NULL) {
    if ($data instanceof ProductAdverts) {
      $route_name = \Drupal::routeMatch()->getRouteName();
      return $route_name === 'view.api_product_adverts.rest_export_1';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []) {

    $data = parent::normalize($entity, $format, $context);
    /** @var ProductInterface $product */
    $product = $entity->get('product_id')->entity;

    $image = null;
    if ($product->hasField('image')) {
      $image = file_create_url($product->get('image')->entity->getFileUri());
    }

    $data['_product'] = [
      'id' => $product->id(),
      'title' => $product->getTitle(),
      'image' => $image,
      'price' => $product->getDefaultVariation()->getPrice()->toArray()
    ];

    $this->addCacheableDependency($context, $product);
    $this->addCacheableDependency($context, $product->getDefaultVariation());

    return $data;
  }

}
