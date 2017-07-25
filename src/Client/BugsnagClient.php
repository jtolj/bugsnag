<?php

namespace Drupal\bugsnag\Client;

use Bugsnag\Client;
use Bugsnag\Handler as BugsnagHandler;
use Drupal\Core\Config\ConfigFactoryInterface;

class BugsnagClient {

  protected $config;
  protected $client;

  /**
   * Constructs a BugsnagClient object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('bugsnag.settings');
    $this->initalizeClient();
  }

  /**
   * Client getter.
   *
   * @return \Bugsnag\Client
   *   A bugsnag client, if initialized sucessfully otherwise null.
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Bootstrap the Bugsnag Client.
   */
  protected function initalizeClient() {
    $apikey = trim($this->config->get('bugsnag_apikey'));
    if (!empty($apikey)) {
      $user = \Drupal::currentUser();

      try {
        $this->client = Client::make($apikey);

        if (!empty($_SERVER['HTTP_HOST'])) {
          $this->client->setHostname($_SERVER['HTTP_HOST']);
        }

        $release_stage = $this->config->get('release_stage');
        if (empty($release_stage)) {
          $release_stage = 'development';
        }
        $this->client->setReleaseStage($release_stage);

        if ($user->id()) {
          $this->client->registerCallback(function ($report) use ($user) {
            $report->setUser([
              'id' => $user->id(),
              'name' => $user->getAccountName(),
              'email' => $user->getEmail(),
            ]);
          });
        }

        if ($this->config->get('bugsnag_log_exceptions')) {
          BugsnagHandler::register($_bugsnag_client);
        }
      }
      catch (\Exception $e) {

      }

    }
  }

}
