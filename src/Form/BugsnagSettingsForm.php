<?php
/**
 * @file
 * Contains \Drupal\bugsnag\Form\BugsnagSettingsForm.
 */

namespace Drupal\bugsnag\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class BugsnagSettingsForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'bugsnag.config_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['api_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Your Bugsnag API Key')
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('api_key')) == 0) {
      $form_state->setErrorByName('api_key', $this->t('You must set an API Key.'));
    } else {
      drupal_set_message(t('API Key is valid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::configFactory()->getEditable('bugsnag.config')
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();

    drupal_set_message(t('API Key successfully saved.'));
  }

}
?>