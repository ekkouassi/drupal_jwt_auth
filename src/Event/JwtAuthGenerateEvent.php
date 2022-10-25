<?php

namespace Drupal\drupal_jwt_auth\Event;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
class JwtAuthGenerateEvent extends JWTTokenBaseEvent {
    /**
     * @param $claim
     * @param $value
     * @return void
     */
  public function addClaim($claim, $value)
  {
    $this->JWTTokenManager->setClaim($claim, $value);
  }

    /**
     * @param $claim
     * @return void
     */
  public function removeClaim($claim)
  {
    $this->JWTTokenManager->unsetClaim($claim);
  }
}
