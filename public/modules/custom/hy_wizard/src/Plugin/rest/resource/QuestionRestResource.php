<?php

namespace Drupal\hy_wizard\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Drupal\hy_wizard\Controller\WizardController;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "question_rest_resource",
 *   label = @Translation("Question rest resource"),
 *   uri_paths = {
 *     "canonical" = "/api/wizard-questions/{tid}"
 *   }
 * )
 */
class QuestionRestResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new QuestionRestResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('hy_wizard'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($tid = NULL) {
    // Lets use our helper Controller. Service would be better.
    // @todo Add a service to handle questions.
    $wizard = new WizardController();
    $terms = $wizard->load();

    // If we have been passed a tid, we can recurse the terms
    // to find the right one.
    if ((int) $tid) {
      $terms = $this->find($terms, $tid);
    }

    // Convert children to an array for frontend traversing.
    if (isset($terms['children'])) {
      $children = [];

      foreach ($terms['children'] as $child) {
        $children[] = $child;
      }

      $terms['children'] = $children;

    }
    else {
      // If there are no childen, we are on the top level.
      $temp = [];
      foreach ($terms as $term) {
        $temp[] = $term;
      }

      $terms = $temp;
    }

    $output['terms'] = $terms;

    // Get breadcrumbs as an array.
    // @todo When wizard is converted to use front end UI library
    // this function becomes redundant.
    $output['breadcrumb'] = $wizard->loadBreadCrumb($tid);

    // One thing good to know is that javascript objects
    // do not retain order so they have to be manually
    // sorted in the front end.
    $response = new ResourceResponse($output);

    // There is no need to cache for now.
    // @todo Set correct cache context.
    $response->addCacheableDependency(NULL);

    return $response;
  }

  /**
   * Recurse through taxonomy to find correct taxonomy values.
   */
  public function find(array $terms, $tid) {

    foreach ($terms as $id => $term) {

      if ($id == $tid) {
        return $term;
      }

      if (
        count($term['children'] > 0) &&
        $found_it = $this->find($term['children'], $tid)) {
        return $found_it;
      }
    }

    return FALSE;

  }

}
