<?php

namespace Drupal\site_notifications\Entity;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\site_notifications\SiteNotificationInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Site Notification entity.
 *
 * @ingroup site_notifications
 *
 * @ContentEntityType(
 *   id = "site_notification",
 *   label = @Translation("Site Notification entity"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\site_notifications\Form\SiteNotificationForm",
 *       "edit" = "Drupal\site_notifications\Form\SiteNotificationForm",
 *       "delete" = "Drupal\site_notifications\Form\SiteNotificationDeleteForm",
 *     },
 *    "access" = "Drupal\site_notifications\SiteNotificationAccessControlHandler",
 *    "list_builder" = "Drupal\site_notifications\Entity\Controller\SiteNotificationListBuilder",
 *    "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *    "views_data" = "Drupal\views\EntityViewsData",
 *    "permission_provider" = "Drupal\Core\Entity\EntityPermissionProvider"
 *   },
 *   base_table = "site_notifications",
 *   revision_table = "site_notification_revision",
 *   revision_data_table = "site_notification_field_revision",
 *   admin_permission = "administer site_notification entity",
 *   field_ui_base_route = "site_notifications.site_notification_settings",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "revision" = "revision_id",
 *     "published" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 *   links = {
 *     "canonical" = "/site_notification/{site_notification}",
 *     "edit-form" = "/site_notification/{site_notification}/edit",
 *     "delete-form" = "/site_notification/{site_notification}/delete",
 *     "collection" = "/site_notification/list"
 *   },
 * )
 */
class SiteNotification extends EditorialContentEntityBase implements SiteNotificationInterface {

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
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
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
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
  public function getMessage() {
    return $this->get('message')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getPosition() {
    return $this->get('position')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getFill() {
    return $this->get('fill')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getLocations() {
    $referenceItem = $this->get('locations')->referencedEntities();
    return $referenceItem;
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
  public function setTitle($title) {
    return $this->set('title', $title);
  }

  /**
   * {@inheritdoc}
   */
  public function getId() {
    return $this->get('id')->value;
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
  public function setMessage($message) {
    $this->set('message', $message);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($uid) {
    $this->set('changed', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($uid) {
    $this->set('created', $uid);
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
  public function getChangedTimeAcrossTranslations() {
    return $this->getChangedTimeAcrossTranslations();
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Site Notification entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Site Notification entity.'))
      ->setReadOnly(TRUE);
    $fields['message'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Notification'))
      ->setDescription(t('Notification to be displayed on selected page(s).'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE)
      ->setRequired(TRUE);
    $fields['all_pages'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t("All Pages"))
      ->setSetting('on_label', t("Will the notification be on all pages?"))
      ->setDisplayOptions('form', [
        'type' => 'boolean',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setRevisionable(TRUE);
    $fields['locations'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Location'))
      ->setDescription(t('The page(s) this notificaiton will appear on.'))
      ->setSetting('target_type', 'node')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => 2,
      ])
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE);
    $fields['position'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Position'))
      ->setDescription(t('Where the notification will display on page.'))
      ->setSettings([
        'allowed_values' => [
          'topleft' => 'Top Left',
          'topcenter' => 'Top Center',
          'topright' => 'Top Right',
          'leftcenter' => 'Left Center',
          'center' => 'Center',
          'rightcenter' => 'Right Center',
          'bottomleft' => 'Bottom Left',
          'bottomcenter' => 'Bottom Center',
          'bottomright' => 'Bottom Right',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'list_default',
        'weight' => 7,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRevisionable(TRUE)
      ->setRequired(TRUE);
    $fields['fill'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t("Full Length"))
      ->setSetting('on_label', t("Will the notification be full length?"))
      ->setDisplayOptions('form', [
        'type' => 'boolean',
        'weight' => 8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setRevisionable(TRUE);
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'))
      ->setRevisionable(TRUE);
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the term was last edited.'))
      ->setTranslatable(TRUE);
    $fields['changed']->setRevisionable(TRUE);
    return $fields;
  }

}
