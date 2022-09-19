<?php

namespace Drupal\drupal_jwt_auth\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\drupal_jwt_auth\JWTToken\JWTTokenManagerInterface;

/**
 * Class JwtAuthBaseEvent.
 *
 * @package Drupal\jwt\Authentication\Event
 */
class JWTTokenBaseEvent extends Event
{
    /**
     * @var \Drupal\drupal_jwt_auth\JWTToken\JWTTokenManagerInterface
     */
    protected $JWTTokenManager;

    /**
     * @param JWTTokenManagerInterface $JWTTokenManager
     */
    public function __construct(JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->JWTTokenManager = $JWTTokenManager;
    }


    /**
     * @return JWTTokenManagerInterface
     */
    public function getToken(): JWTTokenManagerInterface
    {
        return $this->JWTTokenManager;
    }
}
