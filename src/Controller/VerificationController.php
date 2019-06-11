<?php

namespace Drupal\recaptcha_verification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Utility\Error;
use Drupal\recaptcha_verification\RecaptchaVerificationInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class VerificationController.
 *
 * Currently used in building a data layer object.
 *
 */
class VerificationController extends ControllerBase {

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The recaptcha verification service.
   *
   * @var \Drupal\recaptcha_verification\RecaptchaVerificationInterface
   */
  protected $recaptchaVerification;

  /**
   * Constructs a new VerificationController instance.
   *
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   * @param \Drupal\recaptcha_verification\RecaptchaVerificationInterface $recaptcha_verification
   *   The recaptcha verification service.
   */
  public function __construct(LoggerInterface $logger, RecaptchaVerificationInterface $recaptcha_verification) {
    $this->logger = $logger;
    $this->recaptchaVerification = $recaptcha_verification;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {

    /* @var $logger \Psr\Log\LoggerInterface */
    $logger = $container->get('recaptcha.logger');

    /* @var $recaptcha_verification \Drupal\recaptcha_verification\RecaptchaVerificationInterface */
    $recaptcha_verification = $container->get('recaptcha.verification');

    return new static(
      $logger,
      $recaptcha_verification

    );
  }

  /**
   * Method that is used when the route is accessed during lookup.
   *
   * @param string $token
   *   The token to lookup.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|null
   *   response of the google api.
   */
  public function getScore($token) {
    $response = NULL;
    try {
      $response = new JsonResponse($this->recaptchaVerification->verify($token));
    }
    catch (\Exception $e) {

      $this->logger->critical(
        '%type: @message in %function (line %line of %file).',
        Error::decodeException($e)
      );
    }
    return $response;
  }

}
