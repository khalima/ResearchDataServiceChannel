<?php

namespace Drupal\hy_samlauth\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Wraps a samlauth user sync event for event listeners.
 */
class HySamlauthUserLinkEvent extends Event {

  /**
   * The Drupal user account to link.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $account;

  /**
   * The SAML attributes received from the IDP.
   *
   * Single values are typically represented as one-element arrays.
   *
   * @var array
   */
  protected $attributes;

  /**
   * Constructs a samlouth user link event object.
   *
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
