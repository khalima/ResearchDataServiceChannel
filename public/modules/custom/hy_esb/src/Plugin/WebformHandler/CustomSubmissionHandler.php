<?php

namespace Drupal\hy_esb\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Webform submission test handler.
 *
 * @WebformHandler(
 *   id = "hy_esb_webform_handler",
 *   label = @Translation("Service handler"),
 *   category = @Translation("Helsinki university"),
 *   description = @Translation("Alters service webform functionality."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */
class CustomSubmissionHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function preCreate(array $values) {
    $this->displayMessage(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function postCreate(WebformSubmissionInterface $webform_submission) {
    $this->displayMessage(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function postLoad(WebformSubmissionInterface $webform_submission) {
    $this->displayMessage(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function preDelete(WebformSubmissionInterface $webform_submission) {
    $this->displayMessage(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function postDelete(WebformSubmissionInterface $webform_submission) {
    $this->displayMessage(__FUNCTION__);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(WebformSubmissionInterface $webform_submission) {
    $this->displayMessage(__FUNCTION__);
    // @todo Marko adds field 'status' to webform or delegates it to Tuomas
    // @todo Marko double checks that 'efecte_id' is present. No submissions are
    // supposed to go through without it.
    // @todo Marko gathers data or uses a service to normalize webform data
    // @todo Marko sends out a REST call for HY with correct data
    // @todo Marko checks REST call status
    // @todo Marko adds webform status accordingly
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $this->displayMessage(__FUNCTION__, $update ? 'update' : 'insert');
  }

  /**
   * Show messages in drupal messages.
   */
  public function displayMessage($msg) {
    drupal_set_message($msg);
  }

}
