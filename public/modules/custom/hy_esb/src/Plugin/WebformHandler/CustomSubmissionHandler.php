<?php

namespace Drupal\hy_esb\Plugin\WebformHandler;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    LoggerChannelFactoryInterface $logger_factory,
    ConfigFactoryInterface $config_factory,
    EntityTypeManagerInterface $entity_type_manager,
    WebformSubmissionConditionsValidatorInterface $conditions_validator,
    ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(WebformSubmissionInterface $webform_submission) {
    $url = \Drupal::config('esb')->get('url');

    $message = '';
    $data = $webform_submission->getData();

    // We need to manually extract data from webform. Service mix does not
    // understand any excess forms and it crashes if those are found.
    // Lets loop and form fields from key. It is not robust but works for now.
    foreach ($data as $title => $value) {
      $message .= str_replace('_', ' ', $title) . ': ' . Xss::filter($value);
      // Now this is just stupid but only way to get things done quickly. Api
      // does not recognize html nor \n markup.
      $message .= '
';
    }

    // @todo Marko adds field 'status' to webform or delegates it to Tuomas
    // @todo Marko double checks that 'efecte_id' is present. No submissions are
    // supposed to go through without it.
    // @todo Marko gathers data or uses a service to normalize webform data
    // @todo Marko sends out a REST call for HY with correct data
    // @todo Marko checks REST call status
    // @todo Marko adds webform status accordingly

    // Load service node.
    $service = Node::load($data['service']);
    $service_url = Url::fromRoute('entity.node.canonical', ['node' => $service->id()])->setAbsolute()->toString();

    $efecte_category = $service->get('field_efecte_id')->getString();

    if ($efecte_category === '') {
      drupal_set_message('Ei oo asoo APIIN ilman efecte IIDEETÄ!');
      return;
    }

    $output = [];

    $output['title'] = 'Service order: ' . $service->getTitle();
    $output['description'] = 'Service order: ' . $service->getTitle() . ' (' . $service_url . ')';
    $output['description'] .= $message;
    $output['description'] .= '\n\nCATEGORY: ' . $efecte_category;
    $output['customer'] = $data['email'];

    $request_options['json'] = $output;

    try {
      $response = $this->httpClient->post($url, $request_options);
      $status = $response->getStatusCode();

      if ($status === 200) {
        drupal_set_message('All good with following json response: "' . $response->getBody() . '"');
      }
      else {
        drupal_set_message('Parse the error message from json response "' . $response->getBody() . '"', 'error');
        return;
      }
    }
    catch (RequestException $request_exception) {
      drupal_set_message($request_exception->getMessage(), 'error');
      return;
    }

    // All good, set webform status 'ordered'
    /*$submission_data = $webform_submission->getData();
    $submission_data['some_fancy_pants_status'] = 'ordered';
    $webform_submission->setData($submission_data);
    if ($this->isResultsEnabled()) {
    $this->submissionStorage->saveData($webform_submission);
    }*/
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
  }

}
