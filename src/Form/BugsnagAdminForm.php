<?php

/**
 * @file
 * Contains \Drupal\bugsnag\Form\BugsnagAdminForm.
 */

namespace Drupal\bugsnag\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\RfcLogLevel;

class BugsnagAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bugsnag_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bugsnag.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('bugsnag.settings');

    $form['bugsnag_apikey'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('API key'),
      '#description' => t('Bugsnag API key for the application.'),
      '#default_value' => $config->get('bugsnag_apikey'),
    ];

    $release_stage = $config->get('release_stage');
    $form['release_stage'] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Release Stage'),
      '#default_value' => (!empty($release_stage)) ? $release_stage : 'development' ,
      '#options' => [
        'development' => 'development',
        'production' => 'production'
      ],
    ];

    $form['exception_handling'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('Exception handling'),
    ];

    $form['exception_handling']['bugsnag_log_exceptions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Log unhandled errors and exceptions to Bugsnag.'),
      '#default_value' => $config->get('bugsnag_log_exceptions'),
    ];

    $form['bugsnag_logger'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Send log log events of the selected severity to Bugsnag.'),
      '#options' => [
        'severity-' . RfcLogLevel::EMERGENCY => 'Emergency',
        'severity-' . RfcLogLevel::ALERT => 'Alert',
        'severity-' . RfcLogLevel::CRITICAL => 'Critical',
        'severity-' . RfcLogLevel::ERROR => 'Error',
        'severity-' . RfcLogLevel::WARNING => 'Warning',
        'severity-' . RfcLogLevel::NOTICE => 'Notice',
        'severity-' . RfcLogLevel::DEBUG => 'Debug',
        'severity-' . RfcLogLevel::INFO => 'Info'
      ],
      '#default_value' => (NULL !== $config->get('bugsnag_logger')) ? $config->get('bugsnag_logger') : [],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bugsnag.settings');
    $values = $form_state->getValues();

    $config->set('bugsnag_apikey', $values['bugsnag_apikey']);
    $config->set('release_stage', $values['release_stage']);
    $config->set('bugsnag_log_exceptions', $values['bugsnag_log_exceptions']);
    $values['bugsnag_logger'] = array_values($values['bugsnag_logger']);
    $config->set('bugsnag_logger', array_filter($values['bugsnag_logger']));

    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

}
