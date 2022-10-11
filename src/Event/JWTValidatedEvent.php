<?php

namespace Drupal\drupal_jwt_auth\Event;

use Drupal\drupal_jwt_auth\Services\JWTManagerInterface;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

class JWTValidatedEvent extends AbstractJWTEvent
{

  /**
   * @var UserInterface
   */
  protected UserInterface $user;

  public function __construct(JWTManagerInterface $JWTManager)
  {
    $this->user = User::getAnonymousUser();
    parent::__construct($JWTManager);
  }

  public function setUser(UserInterface $user)
  {
    $this->user = $user;
  }

  public function getUser(): UserInterface
  {
    return $this->user;
  }
}
