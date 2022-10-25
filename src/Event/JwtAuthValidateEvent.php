<?php

namespace Drupal\drupal_jwt_auth\Event;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class JwtAuthValidateEvent extends JWTTokenBaseEvent {

  protected $valid = TRUE;
  protected $invalidReason;

  public function invalidate($reason) {
    $this->valid = FALSE;
    $this->invalidReason = $reason;
    $this->stopPropagation();
  }

    /**
     * @return bool
     */
  public function isValid(): bool
  {
    return $this->valid;
  }

    /**
     * @return mixed
     */
  public function invalidReason() {
    return $this->invalidReason;
  }

}
