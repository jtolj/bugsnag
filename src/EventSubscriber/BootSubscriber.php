<?php

namespace Drupal\bugsnag\EventSubscriber;

use Drupal\bugsnag\Client\BugsnagClient;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BootSubscriber implements EventSubscriberInterface {

  /**
   * A Bugsnag Client.
   *
   * @var \Bugsnag\Client
   */
  protected $client;

  /**
   * Constructs a BootSubscriber object.
   *
   * @param \Drupal\bugsnag\Client\BugsnagClient $bugsnag
   *   The BugsnagClient Service.
   */
  public function __construct(BugsnagClient $bugsnag) {
    $this->client = $bugsnag->getClient();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [KernelEvents::REQUEST => ['onEvent', 255]];
  }

  /**
   * Callback for KernelEvents::REQUEST.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The event object.
   */
  public function onEvent(GetResponseEvent $event) {
    // Dummy handler. The service instantiation does everything we need.
  }

}
