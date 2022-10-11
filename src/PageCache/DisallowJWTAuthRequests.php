<?php

namespace Drupal\jwt\PageCache;

use Drupal\Core\PageCache\RequestPolicyInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class DisallowJWTAuthRequests implements RequestPolicyInterface {

  /**
   * {@inheritdoc}
   */
  public function check(Request $request): ?string
  {
    $configData = \Drupal::config('drupal_jwt_auth.settings')->getRawData();
    $auth = $request->headers->get($configData['token_extractors']['authorization_header']['name']);

    if (preg_match(sprintf('/^%s .+/', $configData['token_extractors']['authorization_header']['prefix']), $auth)) {
      return self::DENY;
    }

    return NULL;
  }
}
