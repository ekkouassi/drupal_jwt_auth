<?php
namespace Drupal\drupal_jwt_auth\Event;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class JWTCreatedEvent extends AbstractJWTEvent {

  public function addClaim($claim, $value) {
    $this->JWTTokenManager->setClaim($claim, $value);
  }

  public function removeClaim($claim) {
    $this->JWTTokenManager->unsetClaim($claim);
  }
}
