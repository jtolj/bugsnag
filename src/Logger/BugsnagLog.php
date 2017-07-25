<?php

namespace Drupal\bugsnag\Logger;

use Drupal\bugsnag\Client\BugsnagClient;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;

/**
 * Logs events to Bugsnag.
 */
class BugsnagLog implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * A configuration object containing Bugsnag log settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The bugsnag instance.
   *
   * @var \Bugsnag\Client
   */
  protected $client;

  /**
   * The message's placeholders parser.
   *
   * @var \Drupal\Core\Logger\LogMessageParserInterface
   */
  protected $parser;

  /**
   * Constructs a BugsnagLog object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   * @param \Drupal\bugsnag\Client\BugsnagClient $bugsnag
   *   The BugsnagClient Service.
   * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
   *   The parser to use when extracting message variables.
   */
  public function __construct(ConfigFactoryInterface $config_factory, BugsnagClient $bugsnag, LogMessageParserInterface $parser) {
    $this->client = $bugsnag->getClient();
    $this->config = $config_factory->get('bugsnag.settings');
    $this->parser = $parser;
  }

  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {

    if (empty($this->client)) {
      return;
    }

    try {
      // Get the log levels we've configured to send to bugsnag.
      $configured_levels = $this->config->get('bugsnag_logger');

      $logged_levels = [];
      if (!empty($configured_levels) && is_array($configured_levels)) {
        foreach ($configured_levels as $configured_level) {
          $logged_levels[] = str_replace('severity-', '', $configured_level);
        }
      }

      if (in_array($level, $logged_levels)) {
        // Populate the message placeholders and replace them in the message.
        $message_placeholders = $this->parser->parseMessagePlaceholders($message, $context);
        $message = empty($message_placeholders) ? $message : strtr($message, $message_placeholders);

        // Log the item to bugsnag.
        $this->client->notifyError($context['channel'], strip_tags($message), function ($report) use ($level) {
          if ($level < 2) {
            $severity = 'info';
          }
          elseif ($level === 3) {
            $severity = 'warning';
          }
          else {
            $severity = 'error';
          }
          $report->setSeverity($severity);
        });

      }
    }
    catch (\Exception $e) {

    }
  }

}
