<?php

namespace Drupal\hy_samlauth\Controller;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\samlauth\Controller\SamlController as OriginalSamlController;
use Drupal\hy_samlauth\SamlService;
use Drupal\Core\Path\PathValidator;
use Drupal\hy_samlauth\EventSubscriber\RedirectAnonymousSubscriber;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SamlController extends OriginalSamlController {

  protected $tempStoreFactory;
  protected $store;
  protected $requestStack;

  /**
   * @var PathValidator
   */
  protected $pathValidator;

  /**
   * {@inheritdoc}
   */
  public function __construct(SamlService $saml, RequestStack $request_stack, PathValidator $pathValidator) {
    parent::__construct($saml, $request_stack);
    $this->saml = $saml;
    $this->setPathValidator($pathValidator);
    $this->requestStack = $request_stack;
  }

  /**
   * @param PathValidator $pathValidator
   */
  public function setPathValidator(PathValidator $pathValidator) {
    $this->pathValidator = $pathValidator;
  }

  /**
   * Factory method for dependency injection container.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('samlauth.saml'),
      $container->get('request_stack'),
      $container->get('path.validator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function login() {
    $this->saml->setPostLoginLogoutDestination();
    parent::login();
  }

  /**
   * {@inheritdoc}
   */
  public function logout() {
    $this->saml->setPostLoginLogoutDestination();
    parent::logout();
  }

  /**
   * {@inheritdoc}
   */
  public function acs() {
    try {
      $this->saml->acs();
    }
    catch (\Exception $e) {
      drupal_set_message($e->getMessage(), 'error');
      return new RedirectResponse('/');
    }

    $url = "/";

    $test = $this->requestStack->getCurrentRequest()->cookies->keys();
    drupal_set_message($test);
    if ($this->requestStack->getCurrentRequest()->cookies->has(SamlService::COOKIE_SAML_REDIRECT)) {
      $cookie_url = $this->requestStack->getCurrentRequest()->cookies->get(SamlService::COOKIE_SAML_REDIRECT);
      if ($valid_url = $this->pathValidator->getUrlIfValid($cookie_url)) {
        $url = $valid_url;
      }
    }

    $response = new RedirectResponse($url);
    // Add the $url object as a dependency of whatever you're returning. Probably a response.

//    $this->saml->getPostLoginDestination()->toString();
//    $response = new TrustedRedirectResponse($url);
//    $response->addCacheableDependency($url);
//    $this->saml->removePostLoginLogoutDestination();

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function sls() {
    $this->saml->sls();

    $url = $this->saml->getPostLogoutDestination()->toString(TRUE);
    $response = new TrustedRedirectResponse($url->getGeneratedUrl());
    $response->addCacheableDependency($url);
    $this->saml->removePostLoginLogoutDestination();
    return $response;
  }
}
