<?php

namespace Drupal\custom_module\Validate;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form API callback. Validate element value.
 */
class ValidateEfecteId {
  /**
   * Validates given element.
   *
   * @param array              $element      The form element to process.
   * @param FormStateInterface $formState    The form state.
   * @param array              $form The complete form structure.
   */
  public static function validate(array &$element, FormStateInterface $formState, array &$form) {
    $webformKey = $element['#webform_key'];
    $value = $formState->getValue($webformKey);

    // Efecte ID must exist.
    if ($value === '') {
      $formState->setError($element, 'Efecte ID is mandatory.');
    }
  }
}
