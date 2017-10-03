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

    $build = [];

    // We could add a dynamic parameter for the wizard since then it could
    // be used without any javascript for those who decide to live on the
    // old days or just want to be safe from any threats of internet.
    // @todo Implement a way to have dynamic variable, like:
    // /wizard/{tid}/{tid} so that this function can be used without js.
    $taxonomy_list = $this->load(HY_WIZARD_VOCABULARY_VID);

    $vocabulary = Vocabulary::load(HY_WIZARD_VOCABULARY_VID);

    $build = [
      '#theme' => 'hy_wizard',
      '#data' => $taxonomy_list,
      '#is_admin' => $user->hasPermission('access administration pages'),
      '#title' => $vocabulary->get('name'),
      '#description' => $vocabulary->get('description'),
      '#attached' => [
        'drupalSettings' => [
          'questions' => $taxonomy_list,
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

    $term_array = [
      'name' => $object->name,
      'tid' => $object->tid,
      'description' => $object->description__value,
      'depth' => $object->depth,
      'parents' => $object->parents,
      'weight' => $object->weight,
    ];

    // Maybe add here an alter hook so that other modules can change data
    // that is sent from the controller.
    $tree[$object->tid] = $term_array;

    // Add edit link to the mix.
    $tree[$object->tid]['children'] = [];
    $object_children = &$tree[$object->tid]['children'];
    $children = $this->entityTypeManager->getStorage('taxonomy_term')->loadChildren($object->tid);

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
