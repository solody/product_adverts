<?php

namespace Drupal\product_adverts\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Product adverts entities.
 *
 * @ingroup product_adverts
 */
interface ProductAdvertsInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Product adverts title.
   *
   * @return string
   *   Title of the Product adverts.
   */
  public function getTitle();

  /**
   * Sets the Product adverts title.
   *
   * @param string $title
   *   The Product adverts title.
   *
   * @return \Drupal\product_adverts\Entity\ProductAdvertsInterface
   *   The called Product adverts entity.
   */
  public function setTitle($title);

  /**
   * Gets the Product adverts creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Product adverts.
   */
  public function getCreatedTime();

  /**
   * Sets the Product adverts creation timestamp.
   *
   * @param int $timestamp
   *   The Product adverts creation timestamp.
   *
   * @return \Drupal\product_adverts\Entity\ProductAdvertsInterface
   *   The called Product adverts entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Product adverts published status indicator.
   *
   * Unpublished Product adverts are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Product adverts is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Product adverts.
   *
   * @param bool $published
   *   TRUE to set this Product adverts to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\product_adverts\Entity\ProductAdvertsInterface
   *   The called Product adverts entity.
   */
  public function setPublished($published);

}
