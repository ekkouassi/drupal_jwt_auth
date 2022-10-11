<?php

namespace Drupal\drupal_jwt_auth\Serializer;

use Drupal\drupal_jwt_auth\Services\JWTManagerInterface;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
interface JWTSerializerInterface {

  /**
   * @param $JWT
   * @return JWTManagerInterface
   */
  public function decode($JWT): JWTManagerInterface;

  /**
   * @param JWTManagerInterface $JWT
   * @return JWTManagerInterface
   */
  public function encode(JWTManagerInterface $JWT): JWTManagerInterface;

  /**
   * @param string $secret
   * @return void
   */
  public function setSecret(string $secret): void;

  /**
   * @param string $algorithm
   * @return mixed
   */
  public function setAlgorithm(string $algorithm): mixed;

  /**
   * @param $privateKey
   * @param bool $derivePublicKey
   * @return bool
   */
  public function setPrivateKey($privateKey, bool $derivePublicKey = TRUE): bool;

  /**
   * @param string $publicKey
   * @return mixed
   */
  public function setPublicKey(string $publicKey): mixed;

  /**
   * @param $algorithm
   * @return string|null
   */
  public static function getAlgorithmType($algorithm): ?string;

  /**
   * @return array
   */
  public static function getAlgorithmOptions(): array;
}
