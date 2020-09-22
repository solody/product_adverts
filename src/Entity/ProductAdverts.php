<?php

namespace Drupal\product_adverts\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Product adverts entity.
 *
 * @ingroup product_adverts
 *
 * @ContentEntityType(
 *   id = "product_adverts",
 *   label = @Translation("Product adverts"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\product_adverts\ProductAdvertsListBuilder",
 *     "views_data" = "Drupal\product_adverts\Entity\ProductAdvertsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\product_adverts\Form\ProductAdvertsForm",
 *       "add" = "Drupal\product_adverts\Form\ProductAdvertsForm",
 *       "edit" = "Drupal\product_adverts\Form\ProductAdvertsForm",
 *       "delete" = "Drupal\product_adverts\Form\ProductAdvertsDeleteForm",
 *     },
 *     "access" = "Drupal\product_adverts\ProductAdvertsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\product_adverts\ProductAdvertsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "product_adverts",
 *   admin_permission = "administer product adverts entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/commerce/product_adverts/{product_adverts}",
 *     "add-form" = "/admin/commerce/product_adverts/add",
 *     "edit-form" = "/admin/commerce/product_adverts/{product_adverts}/edit",
 *     "delete-form" = "/admin/commerce/product_adverts/{product_adverts}/delete",
 *     "collection" = "/admin/commerce/product_adverts",
 *   },
 *   field_ui_base_route = "product_adverts.settings"
 * )
 */
class ProductAdverts extends ContentEntityBase implements ProductAdvertsInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string'
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield'
      ])
      ->setRequired(TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setCardinality(1)
      ->setSettings([
        'file_directory' => 'commerce/product_adverts/image/[date:custom:Y]-[date:custom:m]',
        'file_extensions' => 'png gif jpg jpeg',
        'max_filesize' => '5 MB',
        'max_resolution' => '',
        'min_resolution' => '',
        'alt_field' => false,
        'alt_field_required' => true,
        'title_field' => false,
        'title_field_required' => false,
        'handler' => 'default:file',
        'handler_settings' => []
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'image'
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image'
      ])
      ->setRequired(TRUE);

    $fields['product_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Product')
      ->setDescription(t('The product that the adverts relative to.'))
      ->setCardinality(1)
      ->setSetting('target_type', 'commerce_product')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete'
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'entity_reference_label'
      ])
      ->setRequired(FALSE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox'
      ]);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setDescription(t('Who add this record.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
