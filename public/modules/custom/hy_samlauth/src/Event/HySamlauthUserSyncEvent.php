<?php

namespace Drupal\hy_samlauth\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Wraps a samlauth user sync event for event listeners.
 */
class HySamlauthUserSyncEvent extends Event {

  /**
   * The SAML attributes received from the IDP.
   *
   * Single values are typically represented as one-element arrays.
   *
   * @var array
   */
  protected $attributes;


  /**
   * Constructs a samlauth user sync event object.
   *
   * @param \Drupal\user\UserInterface $account
   *   The Drupal user account.
   * @param array $attributes
   *   The SAML attributes received from the IDP.
   */
  public function __construct(array $attributes) {
    $this->attributes = $attributes;
  }

  /**
   * Gets the SAML attributes.
   *
   * @return array
   *   The SAML attributes received from the IDP.
   */
  public function getAttributes() {
    return $this->attributes;
  }

}
