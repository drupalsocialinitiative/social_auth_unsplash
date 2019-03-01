<?php

namespace Drupal\social_auth_unsplash\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\social_auth\Form\SocialAuthSettingsForm;

/**
 * Settings form for Social Auth Unsplash.
 */
class UnsplashAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_unsplash_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_unsplash.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_auth_unsplash.settings');

    $form['unsplash_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Unsplash Application settings'),
      '#open' => TRUE,
      '#description' => $this->t('You need to first create a Unsplash App at <a href="@unsplash-dev">@unsplash-dev</a>',
        ['@unsplash-dev' => 'https://unsplash.com/oauth/applications']),
    ];

    $form['unsplash_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Access Key'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Access Key here.'),
    ];

    $form['unsplash_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Secret key'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Secret key here.'),
    ];

    $form['unsplash_settings']['authorized_redirect_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized redirect URIs'),
      '#description' => $this->t('Copy this value to <em>Authorized redirect URIs</em> field of your Unsplash App settings.'),
      '#default_value' => Url::fromRoute('social_auth_unsplash.callback')->setAbsolute()->toString(),
    ];

    $form['unsplash_settings']['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    ];

    $form['unsplash_settings']['advanced']['scopes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Scopes for API call'),
      '#default_value' => $config->get('scopes'),
      '#description' => $this->t('Define any additional scopes to be requested, separated by a comma (e.g.: public,read_user).<br>
                                  \'public\' scope added by default and always requested.<br>
                                  You can see the full list of valid scopes and their description <a href="@scopes">here</a>.', ['@scopes' => 'https://unsplash.com/documentation#permission-scopes']),
    ];

    $form['unsplash_settings']['advanced']['endpoints'] = [
      '#type' => 'textarea',
      '#title' => $this->t('API calls to be made to collect data'),
      '#default_value' => $config->get('endpoints'),
      '#description' => $this->t('Define the Endpoints to be requested when user authenticates with Unsplash for the first time<br>
                                  Enter each endpoint in different lines in the format <em>endpoint</em>|<em>name_of_endpoint</em>.<br>
                                  <b>For instance:</b><br>
                                  /photos|photos_list<br>'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('social_auth_unsplash.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->set('scopes', $values['scopes'])
      ->set('endpoints', $values['endpoints'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
