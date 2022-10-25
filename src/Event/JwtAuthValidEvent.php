<?php

namespace Drupal\drupal_jwt_auth\Event;

use Drupal\drupal_jwt_auth\JWTToken\JWTTokenManagerInterface;
use Drupal\user\Entity\User;
use Drupal\user\UserInterface;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class JwtAuthValidEvent extends JWTTokenBaseEvent {
  /**
   * Variable holding the user authenticated by the token in the payload.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  public function __construct(JWTTokenManagerInterface $tokenManager) {
    $this->user = User::getAnonymousUser();
    parent::__construct($tokenManager);
  }

  /**
   * Sets the authenticated user that will be used for this request.
   *
   * @param \Drupal\user\UserInterface $user
   *   A loaded user object.
   */
  public function setUser(UserInterface $user) {
    $this->user = $user;
  }

  /**
   * Returns a loaded user to use if the token is validated.
   *
   * @return \Drupal\user\UserInterface
   *   A loaded user object
   */
  public function getUser() {
    return $this->user;
  }
}
