<?php

namespace Drupal\drupal_jwt_auth\Event;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class JWTValidateEvent extends AbstractJWTEvent {

  /**
   * @var bool
   */
  protected bool $valid = TRUE;

  /**
   * @var string
   */
  protected string $invalidReason;

  public function invalidate($reason) {
    $this->valid = FALSE;
    $this->invalidReason = $reason;
    $this->stopPropagation();
  }

  public function isValid(): bool
  {
    return $this->valid;
  }

  public function invalidReason(): string
  {
    return $this->invalidReason;
  }
}
