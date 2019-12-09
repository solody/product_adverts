<?php

namespace Drupal\product_adverts\Controller;

use Drupal\Console\Command\Generate\AjaxCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\product_adverts\TopProductsCreatorInterface;
use Drupal\taxonomy\TermInterface;

/**
 * Class TopProductsController.
 */
class TopProductsController extends ControllerBase {

  /**
   * Filterproducts.
   *
   * @param $category
   * @param $quantity
   * @return string
   *   Return Hello string.
   */
  public function filterProducts($category, $quantity) {
    $response = new AjaxResponse();

    /** @var TopProductsCreatorInterface $creator */
    $creator = \Drupal::service('product_adverts.top_products_creator');
    $elements = $creator->build($category, $quantity);

    $html = \Drupal::service('renderer')->render($elements);

    $response
      ->addCommand(new ReplaceCommand('.product-filter-section', $html));
    return $response;
  }

}
