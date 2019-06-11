<?php

namespace Drupal\recaptcha_verification;

/**
 * @file
 * Recaptcha verification class.
 *
 * Service to perform google recaptcha v3 verification processes.
 */

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Definitions for RecaptchaVerification.
 *
 * This class has batch functionality for
 * certain ManagedDataBase methods.
 */
class RecaptchaVerification implements RecaptchaVerificationInterface {


  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The Guzzle HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * RecaptchaVerification constructor.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   */
  public function __construct(LoggerInterface $logger, ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->logger = $logger;
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public function verify($token) {
    $response = NULL;
    try {

      $response = json_decode($this->httpClient->post('https://www.google.com/recaptcha/api/siteverify', [
        'form_params' => [
          'response' => $token,
          'secret' => $this->configFactory->get('recaptcha_verification.settings')->get('secret_key'),
        ],
        'headers' => [
          'Content-type' => 'application/x-www-form-urlencoded',
        ],
      ])->getBody()->getContents());
    }
    catch (\Exception $e) {
      $error_message = "Could not complete the request to google. Please give me a better message." . $e->getMessage();

      $this->logger->critical($error_message);
    }

    return $response;
  }

}
