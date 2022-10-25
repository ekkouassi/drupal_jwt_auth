<?php

namespace Drupal\drupal_jwt_auth\Transcoder;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\drupal_jwt_auth\Serializer\JWTSerializerInterface;
use Drupal\drupal_jwt_auth\Services\JWTManager;
use Drupal\drupal_jwt_auth\Services\JWTManagerInterface;
use Drupal\jwt\Transcoder\JWTSerializerException;
use Drupal\key\KeyRepositoryInterface;
use Firebase\JWT\JWT;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 */
class JWTSerializer implements JWTSerializerInterface {

  /**
   * The firebase/php-jwt serializer.
   *
   * @var \Firebase\JWT\JWT
   */
  protected JWT $transcoder;

  /**
   * The allowed algorithms with which a JWT can be decoded.
   *
   * @var string
   */
  protected string $algorithm;

  /**
   * The algorithm type we are using.
   *
   * @var string
   */
  protected string $algorithmType;

  /**
   * The key used to encode/decode a JsonWebToken.
   *
   * @var string
   */
  protected ?string $secret = NULL;

  /**
   * The PEM encoded private key used for signing RSA JWTs.
   *
   * @var string|null
   */
  protected ?string $privateKey = NULL;

  /**
   * The PEM encoded public key used to verify signatures on RSA JWTs.
   *
   * @var string|null
   */
  protected ?string $publicKey = NULL;

  /**
   * {@inheritdoc}
   */
  public static function getAlgorithmOptions(): array
  {
    return [
      'HS256' => 'HMAC using SHA-256 (HS256)',
      'HS384' => 'HMAC using SHA-384 (HS384)',
      'HS512' => 'HMAC using SHA-512 (HS512)',
      'RS256' => 'RSASSA-PKCS1-v1_5 using SHA-256 (RS256)',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getAlgorithmType($algorithm): ?string
  {
    switch ($algorithm) {
      case 'HS256':
      case 'HS384':
      case 'HS512':
        return 'jwt_hs';
      case 'RS256':
        return 'jwt_rs';
      default:
        return NULL;
    }
  }

  /**
   * @param JWT $JWT
   * @param ConfigFactoryInterface $configFactory
   * @param KeyRepositoryInterface $key_repo
   */
  public function __construct(JWT $JWT, ConfigFactoryInterface $configFactory, KeyRepositoryInterface $key_repo) {
    $this->transcoder = $JWT;
    $keyId = $configFactory->get('jwt.config')->get('key_id');
    $this->setAlgorithm($configFactory->get('jwt.config')->get('algorithm'));

    if (isset($keyId)) {
      $key = $key_repo->getKey($keyId);
      if (!is_null($key)) {
        $key_value = $key->getKeyValue();
        if ($this->algorithmType == 'jwt_hs') {
          // Symmetric algorithm so we set the secret.
          $this->setSecret($key_value);
        }
        elseif ($this->algorithmType == 'jwt_rs') {
          // Asymmetric algorithm so we set the private key.
          $this->setPrivateKey($key_value);
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setSecret($secret): void
  {
    $this->secret = $secret;
  }

  /**
   * {@inheritdoc}
   */
  public function setAlgorithm($algorithm)
  {
    $this->algorithm = $algorithm;
    $this->algorithmType = $this->getAlgorithmType($algorithm);
  }

  /**
   * {@inheritdoc}
   */
  public function setPrivateKey($private_key, $derive_public_key = TRUE) {
    $key_context = openssl_pkey_get_private($private_key);
    $key_details = openssl_pkey_get_details($key_context);
    if ($key_details === FALSE || $key_details['type'] != OPENSSL_KEYTYPE_RSA) {
      return FALSE;
    }

    $this->privateKey = $private_key;
    if ($derive_public_key) {
      $this->publicKey = $key_details['key'];
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublicKey($publicKey): bool
  {
    $keyContext = openssl_pkey_get_public($publicKey);
    $keyDetails = openssl_pkey_get_details($keyContext);
    if ($keyDetails === FALSE || $keyDetails['type'] != OPENSSL_KEYTYPE_RSA) {
      return FALSE;
    }

    $this->publicKey = $publicKey;
    return TRUE;
  }

  /**
   * {@inheritdoc}
   * @throws JWTSerializerException
   */
  public function decode($JWT): JWTManagerInterface
  {
    $key = $this->getKey('decode');
    $algorithms = [$this->algorithm];
    try {
      $token = $this->transcoder->decode($jwt, $key, $algorithms);
    }
    catch (\Exception $e) {
      throw JWTSerializerException::newFromException($e);
    }
    return new JWTManager($token);
  }

  /**
   * {@inheritdoc}
   */
  public function encode(JWTManagerInterface $JWT)
  {
    $key = $this->getKey('encode');

    if ($key === NULL) {
      return FALSE;
    }

    return $this->transcoder->encode($JWT->getPayload(), $key, $this->algorithm);
  }

  /**
   * Helper function to get the correct key based on operation.
   *
   * @param string $operation
   *   The operation being performed. One of: encode, decode.
   *
   * @return null|string
   *   Returns NULL if opteration is not found. Otherwise returns key.
   */
  protected function getKey($operation) {
    if ($this->algorithmType == 'jwt_hs') {
      return $this->secret;
    }
    elseif ($this->algorithmType == 'jwt_rs') {
      if ($operation == 'encode') {
        return $this->privateKey;
      }
      elseif ($operation == 'decode') {
        return $this->publicKey;
      }
    }
    return NULL;
  }
}
