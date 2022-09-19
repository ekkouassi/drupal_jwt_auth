<?php

namespace Drupal\drupal_jwt_auth;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
final class Events {
    public const AUTHENTICATION_SUCCESS = 'drupal_jwt_auth.on_authentication_success';
    public const AUTHENTICATION_FAILURE = 'drupal_jwt_auth.on_authentication_failure';
    public const TOKEN_CREATED = 'drupal_jwt_auth.on_token_created';
    public const TOKEN_ENCODED = 'drupal_jwt_auth.on_token_encoded';
    public const TOKEN_DECODED = 'drupal_jwt_auth.on_token_decoded';
    public const TOKEN_NOT_FOUND = 'drupal_jwt_auth.on_token_not_found';
    public const TOKEN_INVALID = 'drupal_jwt_auth.on_token_invalid';
    public const TOKEN_EXPIRED = 'drupal_jwt_auth.on_token_expired';
}
