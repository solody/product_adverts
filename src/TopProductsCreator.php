<?php

namespace Drupal\product_adverts;
use Drupal\commerce_product\Entity\Product;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\taxonomy\TermStorageInterface;

/**
 * Class TopProductsCreator.
 */
class TopProductsCreator implements TopProductsCreatorInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new TopProductsCreator object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function build($category = 'all', $quantity = 10)
  {
    $build['#theme'] = 'adverts_top_products';

    $build['#block_title'] = t('BROWSE TOP SELLING PRODUCTS');
    if (\Drupal::routeMatch()->getRouteName() === 'entity.commerce_store.canonical') {
      $build['#block_title'] = t('');
    }

    // Get all the product categories taxonomy terms.
    /** @var TermStorageInterface $termStorage */
    $termStorage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $tree = $termStorage->loadTree('product_categories', 0, null, true);

    $categories = [];
    $categories[] = [
      '#type' => 'link',
      '#title' => t('All'),
      '#url' => \Drupal\Core\Url::fromRoute('product_adverts.top_products_controller_filterProducts', [
        'category' => 'all',
        'quantity' => $quantity
      ]),
      '#attributes' => [
        'class' => 'use-ajax'
      ]
    ];
    foreach ($tree as $item) {
      $link = [
        '#type' => 'link',
        '#title' => $item->label(),
        '#url' => \Drupal\Core\Url::fromRoute('product_adverts.top_products_controller_filterProducts', [
          'category' => $item->id(),
          'quantity' => $quantity
        ]),
        '#attributes' => [
          'class' => 'use-ajax'
        ]
      ];
      $categories[] = $link;
    }

    $build['#categories'] = $categories;

    $products = [];
    $productStorage = \Drupal::entityTypeManager()->getStorage('commerce_product');
    $productQuery = $productStorage->getQuery();
    if ($category !== 'all') $productQuery->condition('categories', $category);
    if (\Drupal::routeMatch()->getRouteName() === 'entity.commerce_store.canonical') {
      $productQuery->condition('stores', 1);
    }
    $productQuery->range(0, $quantity);
    $productQuery->sort('product_id', 'DESC'); // TODO:: Set the 'top' means to something cool.
    $productEntities = Product::loadMultiple($productQuery->execute());

    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('commerce_product');
    foreach ($productEntities as $productEntity) {
      $products[] = $view_builder->view($productEntity, 'teaser');
    }
    $build['#products'] = $products;

    $more_title = t('LOAD MORE');
    $more_url = \Drupal\Core\Url::fromUserInput('/product-search');
    if (\Drupal::routeMatch()->getRouteName() === 'entity.commerce_store.canonical') {
      $more_url = \Drupal\Core\Url::fromUserInput('/product-search/store/'.\Drupal::routeMatch()->getParameter('commerce_store')->id());
      $more_title = t('Search products in store');
    }

    $build['#more'] = [
      '#type' => 'link',
      '#title' => $more_title,
      '#url' => $more_url
    ];

    return $build;
  }
}
