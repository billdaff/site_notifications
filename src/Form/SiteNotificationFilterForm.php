<?php

namespace Drupal\site_notifications\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the site_notification entity edit forms.
 *
 * @ingroup site_notifications
 */
class SiteNotificationFilterForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_notification_filter_form';
  }

  /**
   * Request Stack for request.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  public $requestStack;

  /**
   * Constructor.
   *
   * @param Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Parameter for constructor.
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

  /**
   * Build Exposed Filter for list.
   *
   * @param array $form
   *   Exposed filter form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Exposed form state.
   *
   * @return array
   *   Returns form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $request = $this->requestStack;
    $form['filter'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['form--inline', 'clearfix'],
      ],
    ];

    $form['filter']['message_status'] = [
      '#type' => 'select',
      '#title' => 'Status',
      '#options' => [
        'all' => $this->t('All'),
        'draft' => $this->t('Draft'),
        'published' => $this->t('Published'),
        'expired' => $this->t('Expired'),
      ],
      '#default_value' => $request->getCurrentRequest()->query->get('message_status') ?? 0,
    ];

    $form['actions']['wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['form-item']],
    ];

    $form['actions']['wrapper']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Filter',
    ];

    if ($request->getCurrentRequest()->getQueryString()) {
      $form['actions']['wrapper']['reset'] = [
        '#type' => 'submit',
        '#value' => 'Reset',
        '#submit' => ['::resetForm'],
      ];
    }
    return $form;
  }

  /**
   * Submit Exposed Filter for list.
   *
   * @param array $form
   *   Exposed filter form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Exposed form state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = [];

    $message_status = $form_state->getValue('message_status') ?? 0;
    if ($message_status) {
      $query['message_status'] = $message_status;
    }

    $form_state->setRedirect('entity.site_notification.collection', $query);
  }

  /**
   * Reset Exposed Filter for list.
   *
   * @param array $form
   *   Exposed filter form.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   Exposed form state.
   */
  public function resetForm(array $form, FormStateInterface &$form_state) {
    $form_state->setRedirect('entity.site_notification.collection');
  }

}
