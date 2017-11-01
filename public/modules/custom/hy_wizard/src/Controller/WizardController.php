<?php

namespace Drupal\hy_wizard\Controller;

use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class WizardController.
 */
class WizardController extends ControllerBase {
  /**
   * Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   *
   * @todo Add this as a dependency injection.
   */
  public function __construct() {
    $this->entityTypeManager = \Drupal::entityTypeManager();
  }

  /**
   * Page.
   *
   * @return array
   *   Return Page array.
   */
  public function page() {
    $user = \Drupal::currentUser();
    $config = \Drupal::config('hy_wizard.wizardadmin');

    $build = [];

    // We could add a dynamic parameter for the wizard since then it could
    // be used without any javascript for those who decide to live on the
    // old days or just want to be safe from any threats of internet.
    // @todo Implement a way to have dynamic variable, like:
    // /wizard/{tid}/{tid} so that this function can be used without js.
    $taxonomy_list = $this->load(HY_WIZARD_VOCABULARY_VID);

    // We need this to extract vocabulary info. Probably will change
    // for custom options at some point.
    $vocabulary = Vocabulary::load(HY_WIZARD_VOCABULARY_VID);

    // @todo Add page title
    // @todo Rethink about xss in template.
    // @todo Add more drupalSettings
    $build = [
      '#theme' => 'hy_wizard',
      '#data' => $taxonomy_list,
      '#is_admin' => $user->hasPermission('access administration pages'),
      '#title' => $config->get('title'),
      '#content' => $config->get('content_value'),
      '#description' => $vocabulary->get('description'),
      '#attached' => [
        'drupalSettings' => [
          'questions' => $taxonomy_list,
          'consult_text' => $config->get('consultation_link_text'),
          'consult_target' => $config->get('consultation_link_target'),
        ],
        'library' => ['hy_wizard/hy_wizard_controller'],
      ],
    ];

    return $build;
  }

  /**
   * Loads the tree of a vocabulary.
   *
   * @param string $vocabulary
   *   Machine name.
   *
   * @return array
   *   An array tree of terms.
   */
  public function load($vocabulary = NULL) {
    // Add default value.
    // @todo Add this through a service.
    if (!$vocabulary) {
      $vocabulary = 'hy_wizard';
    }

    $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->loadTree($vocabulary);

    $tree = [];
    foreach ($terms as $tree_object) {
      $this->buildTree($tree, $tree_object, $vocabulary);
    }

    return $tree;
  }

  /**
   * Get breadcrumb trail from tid.
   *
   * @todo Convert this to be in a service.
   */
  public function loadBreadcrumb($tid = NULL) {
    $breadcrumb = [];

    if ($tid) {
      // Load breadcrumb tree for this tid.
      $parents = $this->entityTypeManager()->getStorage('taxonomy_term')->loadAllParents($tid);

      foreach ($parents as $parent_tid => $parent_term) {
        $breadcrumb[] = [
          'name' => $parent_term->getName(),
          'tid' => $parent_term->id(),
        ];
      }
    }

    $base_term = [
      'name' => $this->t('Wizard'),
      'tid' => 0,
    ];

    $breadcrumb[] = $base_term;

    // We need to reverse order so that the array is easier to
    // traverse through in the front end.
    return array_reverse($breadcrumb);
  }

  /**
   * Populates a tree array given a taxonomy term tree object.
   *
   * @param array $tree
   *   A taxonomy tree.
   * @param object $object
   *   A term.
   * @param string $vocabulary
   *   Current vocabulary.
   */
  protected function buildTree(array &$tree, $object, $vocabulary) {
    if ($object->depth != 0) {
      return;
    }

    // We need to access field data also. This might be better off
    // with views.
    $term = $this->entityTypeManager()->getStorage('taxonomy_term')->load($object->tid);

    // Services of a term.
    $services = $term->field_services->referencedEntities();
    $services_array = [];

    if ($services) {
      // Create a simpler array from Drupal Node object.
      foreach ($services as $service) {
        $services_array[] = [
          'body' => $service->get('field_description')->value,
          'title' => $service->getTitle(),
          'nid' => $service->id(),
          'path' => \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $service->id()),
        ];
      }
    }

    // This holds all the variables. REST service does not
    // work if this contains objects.
    $term_array = [
      'name' => $object->name,
      'tid' => $object->tid,
      'description' => $object->description__value,
      'depth' => $object->depth,
      'parents' => $object->parents,
      'services' => $services_array,
      'weight' => $object->weight,
    ];

    // Maybe add here an alter hook so that other modules can change data
    // that is sent from the controller.
    $tree[$object->tid] = $term_array;

    // Add edit link to the mix.
    $tree[$object->tid]['children'] = [];
    $object_children = &$tree[$object->tid]['children'];
    $children = $this
      ->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadChildren($object->tid);

    if (!$children) {
      return;
    }

    $child_tree_objects = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary, $object->tid);

    foreach ($children as $child) {
      foreach ($child_tree_objects as $child_tree_object) {
        if ($child_tree_object->tid == $child->id()) {
          $this->buildTree($object_children, $child_tree_object, $vocabulary);
        }
      }
    }
  }

}
