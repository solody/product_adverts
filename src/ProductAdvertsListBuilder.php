<?php

namespace Drupal\product_adverts;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Product adverts entities.
 *
 * @ingroup product_adverts
 */
class ProductAdvertsListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Product adverts ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\product_adverts\Entity\ProductAdverts */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.product_adverts.edit_form',
      ['product_adverts' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
