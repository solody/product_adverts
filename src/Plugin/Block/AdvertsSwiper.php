<?php

namespace Drupal\product_adverts\Plugin\Block;

use Drupal\commerce_price\CurrencyFormatter;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\product_adverts\Entity\ProductAdverts;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'AdvertsSwiper' block.
 *
 * @Block(
 *  id = "adverts_swiper",
 *  admin_label = @Translation("Adverts swiper"),
 * )
 */
class AdvertsSwiper extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * Constructs a new AdvertsSwiper object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var ProductAdverts[] $product_adverts */
    $product_adverts = ProductAdverts::loadMultiple();
    $data = [];
    /** @var \Drupal\commerce_price\CurrencyFormatter $CurrencyFormatter */
    $CurrencyFormatter = \Drupal::service('commerce_price.currency_formatter');
    foreach ($product_adverts as $advert) {
      $data[] = [
        'title' => $advert->getTitle(),
        'sub_title' => $advert->getSubTitle(),
        'summary' => $advert->getSummary(),
        'image' => $advert->get('image')->entity,
        'product' => $advert->getProduct(),
        'url_to_product' => Url::fromRoute('entity.commerce_product.canonical', ['commerce_product' => $advert->getProduct()->id()]),
        'price_of_product' => $CurrencyFormatter->format(
          $advert->getProduct()->getDefaultVariation()->getPrice()->getNumber(),
          $advert->getProduct()->getDefaultVariation()->getPrice()->getCurrencyCode(),
          [
            'minimum_fraction_digits' => '0',
            'maximum_fraction_digits' => '0',
            'style' => 'accounting'
          ])
      ];
    }
    $build = [];
    $build['adverts_swiper'] = [
      '#theme' => 'adverts_swiper',
      '#adverts' => $data
    ];

    return $build;
  }

  public function getCacheMaxAge()
  {
    return 0;
  }
}
