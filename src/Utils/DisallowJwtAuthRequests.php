<?php

namespace Drupal\drupal_jwt_auth\Utils;

use Drupal\Core\PageCache\RequestPolicyInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class DisallowJwtAuthRequests implements RequestPolicyInterface {

  /**
   * {@inheritdoc}
   */
  public function check(Request $request) {
    $auth = $request->headers->get('Authorization');

    if (preg_match('/^Bearer .+/', $auth)) {
      return self::DENY;
    }

    return NULL;
  }

}
