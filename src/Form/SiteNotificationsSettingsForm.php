<?php

namespace Drupal\site_notifications\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SiteNotificationsSettingsForm.
 *
 * Sets up the form for the Site Notification settings.
 *
 * @package Drupal\site_notifications\Form
 * @ingroup site_notifications
 */
class SiteNotificationsSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'site_notifications_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   An associative array containing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['site_notifications_settings']['#markup'] = 'Settings form for Site Notification. Manage field settings here.';
    return $form;
  }

}
