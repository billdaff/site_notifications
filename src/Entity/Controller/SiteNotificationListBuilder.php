<?php

namespace Drupal\site_notifications\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for site_notification entity.
 *
 * @ingroup site_notifications
 */
class SiteNotificationListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   *
   * We override ::render() so that we can add our own content above the table.
   * parent::render() is where EntityListBuilder creates the table using our
   * buildHeader() and buildRow() implementations.
   */
  public function render() {
    $build['description'] = [
      '#markup' => $this->t('Site Notificaitons implements a notification system for your site. These messages are fieldable entities that are displayed on specified pages. You can manage the fields on the <a href="@adminlink">Site Notifications admin page</a>.', [
        '@adminlink' => \Drupal::urlGenerator()
          ->generateFromRoute('site_notifications.site_notification_settings'),
      ]),
    ];
    $build['form'] = \Drupal::formBuilder()->getForm('Drupal\site_notifications\Form\SiteNotificationFilterForm');

    $build += parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the site message list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['message'] = $this->t('Message');
    $header['locations'] = $this->t('Locations');
    $header['status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    /** @var \Drupal\site_notifications\Entity\SiteNotification $entity */
    $row['message'] = $entity->message->value;
    $urls = $entity->getLocations();
    $row['locations'] = '';
    $all_pages = $entity->get('all_pages')->value;
    if ($all_pages == 0) {
      foreach ($urls as $url) {
        $row['locations'] .= $url->getTitle();
        if (end($urls) != $url) {
          $row['locations'] .= ', ';
        }
      }
    }
    else {
      $row['locations'] = 'All';
    }
    $row['status'] = $entity->get('moderation_state')->value;
    return $row + parent::buildRow($entity);
  }
}
