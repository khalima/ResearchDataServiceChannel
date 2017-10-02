<?php

namespace Drupal\hy_wizard\Controller;

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
   * Page.
   *
   * @return array
   *   Return Page array.
   */
  public function page() {
    $this->entityTypeManager = \Drupal::entityTypeManager();

    $build = [];

    $taxonomy_list = $this->load(WIZARD_VOCABULARY_VID);

    $build = [
      '#theme' => 'hy_wizard',
      '#data' => $taxonomy_list,
      '#attached' => [
        'drupalSettings' => [
          'testvar' => $taxonomy_list,
        ],
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
  public function load($vocabulary) {
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary);
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

    $tree[$object->tid] = $object;
    $tree[$object->tid]->children = [];
    $object_children = &$tree[$object->tid]->children;
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
