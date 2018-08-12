<?php

/**
 * @file
 * Contains product_adverts.page.inc.
 *
 * Page callback for Product adverts entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Product adverts templates.
 *
 * Default template: product_adverts.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_product_adverts(array &$variables) {
  // Fetch ProductAdverts Entity Object.
  $product_adverts = $variables['elements']['#product_adverts'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
