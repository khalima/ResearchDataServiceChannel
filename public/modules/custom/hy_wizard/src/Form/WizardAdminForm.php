<?php

namespace Drupal\hy_wizard\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
    $config = $this->config('hy_wizard.wizardadmin');
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

    $this->config('hy_wizard.wizardadmin')
      ->save();
  }

}
