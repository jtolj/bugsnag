<?php

/**
 * @file
 * Contains \Drupal\bugsnag\Form\BugsnagAdminForm.
 */

namespace Drupal\bugsnag\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bugsnag.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bugsnag.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface &$form_state) {

    $form['bugsnag_apikey'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('API key'),
      '#description' => t('Bugsnag API key for the application.'),
      '#default_value' => \Drupal::config('bugsnag.settings')->get('bugsnag_apikey'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
