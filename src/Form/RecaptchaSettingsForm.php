<?php

namespace Drupal\recaptcha_verification\Form;

use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RecaptchaSettingsForm.
 */
class RecaptchaSettingsForm extends ConfigFormBase {

  /**
   * The library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {

    /* @var $config_factory \Drupal\Core\Config\ConfigFactoryInterface */
    $config_factory = $container->get('config.factory');

    /* @var $library_discovery \Drupal\Core\Asset\LibraryDiscoveryInterface */
    $library_discovery = $container->get('library.discovery');

    return new static(
      $config_factory,
      $library_discovery
    );
  }

  /**
   * Constructs a new HomepageSettingsForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Config Factory service.
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $library_discovery
   *   The library discovery service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LibraryDiscoveryInterface $library_discovery) {
    parent::__construct($config_factory);
    $this->libraryDiscovery = $library_discovery;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'recaptcha_verification.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'recaptcha_verification_settings';
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('recaptcha_verification.settings');

    $form['site_key'] = [
      '#type' => 'textfield',
      '#default_value' => $config->get('site_key'),
      '#title' => $this->t('Google Recaptcha Site Key'),
    ];
    $form['secret_key'] = [
      '#type' => 'textfield',
      '#default_value' => $config->get('secret_key'),
      '#title' => $this->t('Google Recaptcha Secret Key'),
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('status'),
      '#title' => $this->t('Disable Recaptcha from being loaded.'),
    ];
    $form['paths'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Pages to track (optional)'),
      '#default_value' => $config->get('paths'),
      '#description' => $this->t('By default, Google Recaptcha will be loaded on all non-administrative pages'
        . ' within your domain.<br/>'
        . 'If you need to only load this on specific pages, you can specify which pages you want to track '
        . 'by providing the path (everything after .com). Include one path per line. For example,'
        . '<pre>  /home/about<br/>  /posts<br/>  /posts/*<br/>  /users/*/details</pre>'),
      '#cols' => 100,
      '#rows' => 5,
      '#resizable' => FALSE,
      '#required' => FALSE,
      '#weight' => 40,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // On save, we need to rebuild the library definitions to
    // prevent the recaptcha library from caching the old dynamic
    // library definition.
    $this->libraryDiscovery->clearCachedDefinitions();

    $this->config('recaptcha_verification.settings')
      ->set('secret_key', $form_state->getValue('secret_key'))
      ->set('site_key', $form_state->getValue('site_key'))
      ->set('status', $form_state->getValue('status'))
      ->set('paths', $form_state->getValue('paths'))
      ->save();
  }

}
