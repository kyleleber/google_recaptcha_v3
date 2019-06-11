<?php

namespace Drupal\recaptcha_verification;

/**
 * RecaptchaVerificationInterface definition.
 */
interface RecaptchaVerificationInterface {

  /**
   * Makes a request to the google API to look up score.
   *
   * @param string $token
   *   The client authorization token.
   *
   * @return null|mixed
   *   The response of the lookup.
   */
  public function verify($token);

}
