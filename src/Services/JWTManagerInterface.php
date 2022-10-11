<?php

namespace Drupal\drupal_jwt_auth\Services;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
interface JWTManagerInterface
{
  /**
   * @return mixed
   */
  public function getPayload(): mixed;

  /**
   * @param $claim
   * @return mixed
   */
  public function getClaim($claim): mixed;

  /**
   * @param $claim
   * @param $value
   * @return void
   */
  public function setClaim($claim, $value): void;

  /**
   * @param $claim
   * @return void
   */
  public function unsetClaim($claim): void;
}
