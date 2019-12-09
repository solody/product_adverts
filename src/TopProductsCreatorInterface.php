<?php

namespace Drupal\product_adverts;

/**
 * Interface TopProductsCreatorInterface.
 */
interface TopProductsCreatorInterface {
  public function build($category = 'all', $quantity = 10);
}
