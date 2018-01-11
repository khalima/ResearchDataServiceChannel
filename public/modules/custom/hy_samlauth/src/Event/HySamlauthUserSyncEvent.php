<?php

namespace Drupal\hy_samlauth\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Wraps a samlauth user sync event for event listeners.
 */
class HySamlauthUserSyncEvent extends Event {

  const USER_COOKIE = 'hy_samlauth.user_cookie';

  /**
   * The SAML attributes received from the IDP.
   *
   * Single values are typically represented as one-element arrays.
   *
   * @var array
   */
  protected $attributes;
  protected $session;

  /**
   * Constructs a samlauth user sync event object.
   *
   * @param array $attributes
   *   The SAML attributes received from the IDP.
   */
  public function __construct(array $attributes) {
    $this->attributes = $attributes;
    $this->session = \Drupal::service('user.private_tempstore')->get('hy_samlauth');
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

  /**
   * Get session.
   *
   * @return mixed
   */
  public function getSession() {
    return $this->session;
  }

  /**
   * Set attributes to session.
   *
   * @param $name
   *   Session attribute name.
   * @param $value
   *   Session attribute value.
   * @return
   */
  public function setAttribute($name, $value) {
    return $this->session->set($name, $value);
  }

  /**
   * Get attribute from session.
   *
   * @param $name
   *   Session attribute name.
   * @return
   */
  public function getAttribute($name) {
    return $this->session->get($name);
  }
}
