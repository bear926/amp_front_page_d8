<?php

namespace Drupal\amp_front_page\Form;

use Drupal\amp\Form\AmpSettingsForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the configuration export form.
 */
class AmpFrontPageSettingsForm extends AmpSettingsForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the amp.settings configuration
    $amp_config = $this->config('amp.settings');

    // Get the original form from the class we are extending
    $form = parent::buildForm($form, $form_state);

    $form['amp_enable_page_pattern_group'] = [
      '#type' => 'fieldset',
      '#title' => t('Enable page pattern'),
      '#tree' => FALSE,
    ];
    $form['amp_enable_page_pattern_group']['amp_front_page_pattern'] = [
      '#type' => 'textarea',
      '#rows' => '7',
      '#title' => t('Enable page'),
      '#description' => t('For example /node/*, /taxonomy/term/{taxonomy_term}, /about-us'),
      '#default_value' => $amp_config->get('amp_front_page_pattern'),
    ];
    $form['amp_disable_page_pattern_group'] = [
      '#type' => 'fieldset',
      '#title' => t('Disable page pattern'),
      '#tree' => FALSE,
    ];
    $form['amp_disable_page_pattern_group']['amp_front_page_pattern_disable'] = [
      '#type' => 'textarea',
      '#rows' => '7',
      '#title' => t('Disable page'),
      '#description' => t('For example /node/*, /taxonomy/term/{taxonomy_term}, /about-us'),
      '#default_value' => $amp_config->get('amp_front_page_pattern_disable'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $amp_config = $this->config('amp.settings');
    $amp_config->set('amp_front_page_pattern', $form_state->getValue('amp_front_page_pattern'));
    $amp_config->set('amp_front_page_pattern_disable', $form_state->getValue('amp_front_page_pattern_disable'));
    $amp_config->save();

    parent::submitForm($form, $form_state);
  }

}
