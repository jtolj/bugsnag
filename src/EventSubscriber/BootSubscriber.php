<?php

namespace Drupal\bugsnag\EventSubscriber;

use Bugsnag\Client as BugsnagClient;
use Bugsnag\Handler as BugsnagHandler;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BootSubscriber implements EventSubscriberInterface {

  /**
   * A configuration object containing Bugsnag log settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a BootSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('bugsnag.settings');
  }


  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 255]];
  }

  public function onEvent(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event) {
    $apikey = trim($this->config->get('bugsnag_apikey'));
    global $bugsnag;
    if (!empty($apikey) && empty($bugsnag)) {
      $user = \Drupal::currentUser();
      $bugsnag = BugsnagClient::make($apikey);
      $bugsnag->setHostname($_SERVER['HTTP_HOST']);

      if ($user->id()) {
        $bugsnag->registerCallback(function ($report) use ($user) {
          $report->setUser([
            'id' => $user->id(),
            'name' => $user->getAccountName(),
            'email' => $user->getEmail(),
          ]);
        });
      }

      if ($this->config->get('bugsnag_log_exceptions')) {
        BugsnagHandler::register($bugsnag);
      }

    }
  }

}
