<?php /**
 * @file
 * Contains \Drupal\bugsnag\EventSubscriber\BootSubscriber.
 */

namespace Drupal\bugsnag\EventSubscriber;

use Bugsnag_Client;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BootSubscriber implements EventSubscriberInterface {
  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 0]];
  }

  public function onEvent(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event) {
    $bugsnagLibrary = libraries_detect('bugsnag');
    $apikey = trim(\Drupal::config('bugsnag.settings')->get('bugsnag_apikey'));
    if (!empty($bugsnagLibrary) && !empty($apikey)) {
      $bugsnagLibraryPath = $bugsnagLibrary['library path'] . '/src/Bugsnag/Autoload.php';
      $user = \Drupal::currentUser();
      global $bugsnag_client;

      require_once $bugsnagLibraryPath;
      $bugsnag_client = new Bugsnag_Client(\Drupal::config('bugsnag.settings')->get('bugsnag_apikey'));

      if ($user->uid) {
        $bugsnag_client->setUser([
          'id' => $user->uid,
          'name' => $user->name,
          'email' => $user->mail,
        ]);
      }

      set_error_handler([$bugsnag_client, 'errorHandler']);
      set_exception_handler([$bugsnag_client, 'exceptionHandler']);

    }
  }

}
