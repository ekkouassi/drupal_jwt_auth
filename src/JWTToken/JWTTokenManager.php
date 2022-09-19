<?php

namespace Drupal\drupal_jwt_auth\JWTToken;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
class JWTTokenManager implements JWTTokenManagerInterface {

  protected $payload;

  public function __construct($jwt = NULL) {
    $jwt = (is_null($jwt)) ? new \stdClass() : $jwt;
    $this->payload = $jwt;
  }

  public function getPayload() {
    return $this->payload;
  }

  public function getClaim($claim) {
    $payload = $this->payload;
    return $this->internalGetClaim($payload, $claim);
  }

  public function setClaim($claim, $value) {
    $payload = $this->payload;
    $this->internalSetClaim($payload, $claim, $value);
    $this->payload = $payload;
  }

  public function unsetClaim($claim) {
    $payload = $this->payload;
    $this->internalUnsetClaim($payload, $claim);
    $this->payload = $payload;
  }

  protected function internalGetClaim(&$payload, $claim) {
    $current_claim = (is_array($claim)) ? array_shift($claim) : $claim;

    if (!isset($payload->$current_claim)) {
      return NULL;
    }

    if (is_array($claim) && count($claim) > 0) {
      return $this->internalGetClaim($payload->$current_claim, $claim);
    }
    else {
      return $payload->$current_claim;
    }
  }

  protected function internalSetClaim(&$payload, $claim, $value) {
    $current_claim = (is_array($claim)) ? array_shift($claim) : $claim;

    if (is_array($claim) && count($claim) > 0) {
      if (!isset($payload->$current_claim)) {
        $payload->$current_claim = new \stdClass();
      }

      $this->internalSetClaim($payload->$current_claim, $claim, $value);
    }
    else {
      $payload->$current_claim = $value;
    }
  }

  protected function internalUnsetClaim(&$payload, $claim) {
    $current_claim = (is_array($claim)) ? array_shift($claim) : $claim;

    if (!isset($payload->$current_claim)) {
      return;
    }

    if (is_array($claim) && count($claim) > 0) {
      $this->internalUnsetClaim($payload->$current_claim, $claim);
    }
    else {
      unset($payload->$current_claim);
    }
  }
}
