<?php

namespace Drupal\drupal_jwt_auth\Plugin\KeyType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\key\Plugin\KeyTypeBase;
use Drupal\key\Plugin\KeyPluginFormInterface;

/**
 * @author Ernest KOUASSI<ernestkouassi02@gmail.com>
 *
 * Defines a key type for JWT RSA Signatures.
 *
 * @KeyType(
 *   id = "jwt_rs",
 *   label = @Translation("JWT RSA Key"),
 *   description = @Translation("A key type used for JWT RSA signature algorithms."),
 *   group = "privatekey",
 *   key_value = {
 *     "plugin" = "textarea_field"
 *   }
 * )
 */
class JwtRsKeyType extends KeyTypeBase implements KeyPluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array
  {
    return [
      'algorithm' => 'RS256',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state): array
  {
    $algorithmOptions = [
      'RS256' => $this->t('RSASSA-PKCS1-v1_5 using SHA-256 (RS256)'),
    ];

    $algorithm = $this->getConfiguration()['algorithm'];

    $form['algorithm'] = array(
      '#type' => 'select',
      '#title' => $this->t('JWT Algorithm'),
      '#description' => $this->t('The JWT Algorithm to use with this key.'),
      '#options' => $algorithmOptions,
      '#default_value' => $algorithm,
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->setConfiguration($form_state->getValues());
  }

  /**
   * {@inheritdoc}
   */
  public static function generateKeyValue(array $configuration): string
  {
    $algorithmKeySize = self::getAlgorithmKeySize();
    $algorithm = $configuration['algorithm'];

    if (empty($algorithm) || !isset($algorithmKeySize[$algorithm])) {
      $algorithm = 'RS256';
    }

    $keyResource = openssl_pkey_new([
      'private_key_bits' => $algorithmKeySize[$algorithm],
      'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    $keyString = '';

    openssl_pkey_export($keyResource, $keyString);
    openssl_pkey_free($keyResource);

    return $keyString;
  }

  /**
   * {@inheritdoc}
   */
  public function validateKeyValue(array $form, FormStateInterface $form_state, $key_value) {
    if (!$form_state->getValue('algorithm')) {
      return;
    }

    $algorithm = $form_state->getValue('algorithm');

    $keyResource = openssl_pkey_get_private($key_value);
    if ($keyResource === FALSE) {
      $form_state->setErrorByName('algorithm', $this->t('Invalid Private Key.'));
    }

    $keyDetails = openssl_pkey_get_details($keyResource);

    if ($keyDetails === FALSE) {
      $form_state->setErrorByName('algorithm', $this->t('Unable to get private key details.'));
    }

    $requiredBits = self::getAlgorithmKeySize()[$algorithm];

    if ($keyDetails['bits'] < $requiredBits) {
      $form_state->setErrorByName('algorithm', $this->t('Key size (%size bits) is too small for algorithm chosen. Algorithm requires a minimum of %required bits.', ['%size' => $key_details['bits'], '%required' => $required_bits]));
    }

    if ($keyDetails['type'] != OPENSSL_KEYTYPE_RSA) {
      $form_state->setErrorByName('algorithm', $this->t('Key must be RSA.'));
    }

    openssl_pkey_free($keyResource);
  }

  protected static function getAlgorithmKeySize(): array
  {
    return [
      'RS256' => 2048,
    ];
  }
}
