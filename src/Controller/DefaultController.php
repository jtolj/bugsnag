<?php
/**
 * @file
 * Contains \Drupal\bugsnag\Controller\DefaultController.
 */

namespace Drupal\bugsnag\Controller;

use Drupal\Core\Controller\ControllerBase;

class DefaultController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Hello World!'),
    );
    return $build;
  }

}
?>
