<?php

namespace Drupal\product_adverts;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Product adverts entity.
 *
 * @see \Drupal\product_adverts\Entity\ProductAdverts.
 */
class ProductAdvertsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\product_adverts\Entity\ProductAdvertsInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view product adverts entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit product adverts entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete product adverts entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add product adverts entities');
  }

}
