<?php

namespace Drupal\product_adverts\Entity;

use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\UserInterface;

/**
 * Defines the Product adverts entity.
 *
 * @ingroup product_adverts
 *
 * @ContentEntityType(
 *   id = "product_adverts",
 *   label = @Translation("Product Adverts"),
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
 *     "owner" = "user_id",
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
  use EntityOwnerTrait;

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
  public function getSubTitle() {
    return $this->get('sub_title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSubTitle($sub_title) {
    $this->set('sub_title', $sub_title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    return $this->get('summary')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSummary($summary) {
    $this->set('summary', $summary);
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

    $fields['sub_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Sub Title'))
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
      ->setRequired(false);

    $fields['summary'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Summary'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'basic_string'
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea'
      ])
      ->setRequired(false);

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
      ->setRequired(false);

    $fields['embed'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Embed media'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'basic_string'
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea'
      ])
      ->setRequired(false);

    $fields['product_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Product')
      ->setDescription(t('Product to show.'))
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
      ->setRequired(false);

    $fields['placements'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Placements'))
      ->setDescription(t('One advert can be placed to one or more placements.<a href=":url">Manage placements</a>', [
        ':url' => '/admin/structure/taxonomy/manage/adverts_placements/overview']))
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setSetting('display_description', true)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'adverts_placements'
        ]
      ])
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label'
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_buttons'
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publish status'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox'
      ]);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
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

  /**
   * @return ProductInterface
   */
  public function getProduct()
  {
    return $this->get('product_id')->entity;
  }
}
