<?php

namespace Drupal\drupal_jwt_auth\JWTToken;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
interface JWTTokenManagerInterface
{
    /**
     * @return mixed
     */
    public function getPayload();

    /**
     * @param $claim
     * @return mixed
     */
    public function getClaim($claim);

    /**
     * @param $claim
     * @param $value
     * @return mixed
     */
    public function setClaim($claim, $value);

    /**
     * @param $claim
     * @return mixed
     */
    public function unsetClaim($claim);
}
