<?php

namespace Drupal\site_notifications\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the site_notification entity edit forms.
 *
 * @ingroup site_notifications
 */
class SiteNotificationForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /**
* @var \Drupal\site_notifications\Entity\SiteNotification $entity
*/
    $form = parent::buildForm($form, $form_state);

    $form['all_pages']['widget']['value']['#ajax'] = [
      'callback' => '::siteNotificationSetAllPagesAjaxFunction',
      'event' => 'change',
      'disable-refocus' => TRUE,
      'progress' => [
        'type' => 'throbber',
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);

    if ($status == SAVED_UPDATED) {
      $this->messenger()
        ->addMessage($this->t('The notification has been updated.'));
    }
    else {
      $this->messenger()
        ->addMessage($this->t('The notification has been added.'));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $status;
  }

  /**
   * {@inheritdoc}
   */
  public function siteNotificationSetAllPagesAjaxFunction(array &$form, FormStateInterface &$form_state, $form_id) {
    $values = $form_state->getValues();
    $response = new AjaxResponse();
    if ($values['all_pages']['value'] == 1) {
      $response->addCommand(
            new InvokeCommand(
                '#edit-locations-wrapper',
                'attr',
                ['hidden', 'hidden']
            )
        );

    }
    else {
      $response->addCommand(
            new InvokeCommand(
                '#edit-locations-wrapper',
                'removeAttr',
                ['hidden']
            )
        );
    }
    return $response;
  }

}
