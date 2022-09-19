<?php

namespace Drupal\drupal_jwt_auth\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @author Ernest KOUASSI <ernestkouassi02@gmail.com>
 */
class JWTSettingForm extends ConfigFormBase
{

  protected function getEditableConfigNames()
  {
    return [
      'jwt_auth.settings'
    ];
  }

  public function getFormId()
  {
    return 'jwt_auth_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('jwt_auth.settings');
    $form = parent::buildForm($form, $form_state);

    $form['token_ttl'] = [
      '#type' => 'number',
      '#title' => $this->t('The lifetime of the token in seconds'),
      '#default_value' => $config->get('token_ttl'),
      '#description' => $this->t('This option allows you to specify the lifetime of the token that has been generated'),
    ];
    $form['user_identity_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User identity field'),
      '#default_value' => $config->get('user_identity_field'),
      '#description' => $this->t('The user\'s login field.')
    ];
    $form['authorization_header'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('HTTP headers authentication method'),
    ];
    $form['authorization_header']['authorization_header_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#default_value' => $config->get('token_extractors')['authorization_header']['enabled']
    ];
    $form['authorization_header']['authorization_header_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('JWT token prefix in header'),
      '#default_value' => $config->get('token_extractors')['authorization_header']['prefix']
    ];
    $form['authorization_header']['authorization_header_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Authentication attribute name in header'),
      '#default_value' => $config->get('token_extractors')['authorization_header']['name']
    ];
    $form['cookie'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('HTTP headers authentication method'),
    ];
    $form['cookie']['cookie_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#default_value' => $config->get('token_extractors')['cookie']['enabled']
    ];
    $form['cookie']['cookie_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('HTTP cookies authentication attribute name in header'),
      '#default_value' => $config->get('token_extractors')['cookie']['name']
    ];
    $form['query_parameter'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('HTTP query parameters authentication method'),
    ];
    $form['query_parameter']['query_parameter_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#default_value' => $config->get('token_extractors')['query_parameter']['enabled']
    ];
    $form['query_parameter']['query_parameter_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('HTTP query parameters authentication attribute name in header'),
      '#default_value' => $config->get('token_extractors')['query_parameter']['name']
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $tokenExtractors = [
      'authorization_header' => [
        'enabled' => (bool) $form_state->getValue('authorization_header_enabled'),
        'prefix' => $form_state->getValue('authorization_header_prefix'),
        'name' => $form_state->getValue('authorization_header_name'),
      ],
      'cookie' => [
        'enabled' => (bool) $form_state->getValue('cookie_enabled'),
        'name' => $form_state->getValue('cookie_name')
      ],
      'query_parameter' => [
        'enabled' => (bool) $form_state->getValue('query_parameter_enabled'),
        'name' => $form_state->getValue('query_parameter_name')
      ]
    ];
    $this->config('jwt_auth.settings')
      ->set('token_ttl', (int) $form_state->getValue('token_ttl'))
      ->set('user_identity_field', $form_state->getValue('user_identity_field'))
      ->set('token_extractors', $tokenExtractors)
      ->save();
  }
}
