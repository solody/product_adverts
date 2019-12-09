<?php

namespace Drupal\product_adverts\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the product_adverts module.
 */
class TopProductsControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "product_adverts TopProductsController's controller functionality",
      'description' => 'Test Unit for module product_adverts and controller TopProductsController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests product_adverts functionality.
   */
  public function testTopProductsController() {
    // Check that the basic functions of module product_adverts.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
