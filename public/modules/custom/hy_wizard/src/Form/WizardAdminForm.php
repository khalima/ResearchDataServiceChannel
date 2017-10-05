<?php

namespace Drupal\hy_wizard\Form;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

// @todo Add Xss and HTML from component utility.
// use Drupal\Component\Utility;
/**
 * Class WizardAdminForm.
 */
class WizardAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'hy_wizard.wizardadmin',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wizard_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // @todo this should be gotten from a service probably.
    $config = $this->config('hy_wizard.wizardadmin');

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Wizard title'),
      '#default_value' => $config->get('title'),
    ];

    $form['content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Wizard frontpage content'),
      '#default_value' => $config->get('content_value'),
      '#format' => $config->get('content_format'),
    ];

    // Wizard attributes start here.
    $form['consultation_link_target'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consultation link url'),
      '#default_value' => $config->get('consultation_link_target'),
    ];

    $form['consultation_link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Consultation link text'),
      '#default_value' => $config->get('consultation_link_text'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Comment to surpress validation error in phpcs.
    $var = '';
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    // @todo Add filter xss to prevent malicious code.
    $content = Xss::filterAdmin($form_state->getValue('content')['value']);
    $format = Html::escape($form_state->getValue('content')['format']);
    $consultation_link_target = Html::escape($form_state->getValue('consultation_link_target'));
    $consultation_link_text = Html::escape($form_state->getValue('consultation_link_text'));

    $this->config('hy_wizard.wizardadmin')
      ->set('title', $form_state->getValue('title'))
      ->set('content_value', $content)
      ->set('content_format', $format)
      ->set('consultation_link_target', $consultation_link_target)
      ->set('consultation_link_text', $consultation_link_text)
      ->save();
  }

}
