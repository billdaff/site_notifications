<?php

namespace Drupal\site_notifications;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Site Notification entity.
 *
 * @ingroup site_notifications
 */
interface SiteNotificationInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
