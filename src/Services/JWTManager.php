<?php

namespace Drupal\drupal_jwt_auth\Services;

use stdClass;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
class JWTManager implements JWTManagerInterface
{

  /**
   * @var mixed|stdClass
   */
  protected mixed $payload;

  public function __construct($JWT = NULL)
  {
    $JWT = (is_null($JWT)) ? new stdClass() : $JWT;
    $this->payload = $JWT;
  }

  /**
   * @return mixed
   */
  public function getPayload(): mixed
  {
    return $this->payload;
  }

  /**
   * @param $claim
   * @return mixed
   */
  public function getClaim($claim): mixed
  {
    return $this->internalGetClaim($this->payload, $claim);
  }

  public function setClaim($claim, $value): void
  {
    $payload = $this->payload;
    $this->internalSetClaim($payload, $claim, $value);
    $this->payload = $payload;
  }

  public function unsetClaim($claim): void
  {
    $payload = $this->payload;
    $this->internalUnsetClaim($payload, $claim);
    $this->payload = $payload;
  }

  protected function internalGetClaim(&$payload, $claim)
  {
    $currentClaim = (is_array($claim)) ? array_shift($claim) : $claim;

    if (FALSE === isset($payload->$currentClaim)) {
      return NULL;
    }

    if (TRUE === is_array($claim) && 0 < count($claim)) {
      return $this->internalGetClaim($payload->$currentClaim, $claim);
    } else {
      return $payload->$currentClaim;
    }
  }

  protected function internalSetClaim(&$payload, $claim, $value)
  {
    $currentClaim = (is_array($claim)) ? array_shift($claim) : $claim;

    if (TRUE === is_array($claim) && 0 < count($claim)) {
      if (!isset($payload->$currentClaim)) {
        $payload->$currentClaim = new stdClass();
      }

      $this->internalSetClaim($payload->$currentClaim, $claim, $value);
    } else {
      $payload->$currentClaim = $value;
    }
  }

  protected function internalUnsetClaim(&$payload, $claim)
  {
    $currentClaim = (TRUE === is_array($claim)) ? array_shift($claim) : $claim;

    if (FALSE === isset($payload->$currentClaim)) {
      return;
    }

    if (TRUE === is_array($claim) && 0 < count($claim)) {
      $this->internalUnsetClaim($payload->$currentClaim, $claim);
    } else {
      unset($payload->$currentClaim);
    }
  }
}
