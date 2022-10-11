<?php

namespace Drupal\drupal_jwt_auth\Event;

use Drupal\drupal_jwt_auth\Services\JWTManagerInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class AbstractJWTEvent extends Event
{
  /**
   * @var JWTManagerInterface
   */
    protected JWTManagerInterface $JWTTokenManager;

    public function __construct(JWTManagerInterface $JWTTokenManager)
    {
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function getToken(): JWTManagerInterface
    {
        return $this->JWTTokenManager;
    }
}
