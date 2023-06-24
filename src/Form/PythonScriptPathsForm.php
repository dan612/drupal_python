<?php

namespace Drupal\drupal_python\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PythonScriptPathsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'drupal_python.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drupal_python_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['scripts_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Python Scripts Path'),
      '#default_value' => $config->get('scripts_path'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('scripts_path', $form_state->getValue('scripts_path'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
