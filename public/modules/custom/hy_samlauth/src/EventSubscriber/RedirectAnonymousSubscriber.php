<?php
namespace Drupal\hy_samlauth\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Path\PathValidator;
use Drupal\hy_samlauth\SamlService;


/**
 * Event subscriber subscribing to KernelEvents::REQUEST.
 */
class RedirectAnonymousSubscriber implements EventSubscriberInterface {

  protected $requestStack;
  protected $pathValidator;

  public function __construct(RequestStack $request_stack, PathValidator $pathValidator) {
    $this->pathValidator = $pathValidator;
    $this->requestStack = $request_stack;
  }

  public function checkAuthStatus(GetResponseEvent $event) {

    // Is current path correct path?
    $current_path = \Drupal::url('<current>', [], ['absolute' => FALSE]);
    if (strpos($current_path, '/service-order-form') !== 0) {
      return;
    }

    // Check if user has logged in already.
    $tempstore = \Drupal::service('user.private_tempstore')->get('hy_samlauth');
    $user = $tempstore->get(SamlService::SESSION_SAML_USER);
    if (empty($user)) {
      $response = new RedirectResponse('/saml/login', 301);

      $cookie_saml_redirect = new Cookie(SamlService::COOKIE_SAML_REDIRECT, serialize(\Drupal::request()->getRequestUri()));
      $response->headers->setCookie($cookie_saml_redirect);
      $event->setResponse($response);
      $event->stopPropagation();
    }
  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkAuthStatus');
    return $events;
  }

}
