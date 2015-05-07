<?php

/**
 * @file bugsnag.admin.inc
 */


/**
 * Administration form callback for Bugsnag settings.
 */
function bugsnag_admin_form($form, &$form_state) {

  $form['bugsnag_apikey'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#title' => t('API key'),
    '#description' => t('Bugsnag API key for the application.'),
    '#default_value' => variable_get('bugsnag_apikey', ''),
  );
  
  return system_settings_form($form);
}