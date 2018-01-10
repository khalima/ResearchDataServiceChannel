<?php

namespace Drupal\hy_samlauth;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Path\PathValidator;
use Drupal\Core\Url;
use Drupal\externalauth\ExternalAuth;
use Drupal\samlauth\SamlService as OriginalSamlService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\hy_samlauth\EventSubscriber\RedirectAnonymousSubscriber;
use Drupal\hy_samlauth\Event\HySamlauthUserLinkEvent;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
use Drupal\user\UserInterface;

class SamlService extends OriginalSamlService {

  const SESS_VALUE_KEY = 'postLoginLogoutDestination';
  const COOKIE_SAML_REDIRECT = 'UHRDS_REDIRECT_URL';
  const COOKIE_SAML_NAME = 'UHRDS_USER_NAME';
  const COOKIE_SAML_USER = 'UHRDS_USER_USERNAME';
  const COOKIE_SAML_EMAIL = 'UHRDS_USER_EMAIL';


  /**
   * @var RequestStack
   */
  protected $requestStack;
  /**
   * @var Session
   */
  protected $session;
  /**
   * @var PathValidator
   */
  protected $pathValidator;

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;

  /**
   * Constructor for Drupal\hy_samlauth\SamlService.
   *
   * @param \Drupal\externalauth\ExternalAuth $external_auth
   *   The ExternalAuth service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManager service.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   Event dispatcher
   * @param \Symfony\Component\HttpFoundation\Session\Session
   *   Session.
   * @param \Drupal\Core\Path\PathValidator
   *   Path validator.
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   *   Fill me in.
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   *   Fill me in
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   fill me in.
   */
  public function __construct(ExternalAuth $external_auth, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, LoggerInterface $logger, EventDispatcherInterface $event_dispatcher, RequestStack $requestStack, Session $session, PathValidator $pathValidator) {
    parent::__construct($external_auth, $config_factory, $entity_type_manager, $logger, $event_dispatcher);
    $this->setRequestStack($requestStack);
    $this->setSession($session);
    $this->setPathValidator($pathValidator);
  }

  /**
   * @param Session $session
   */
  public function setSession(Session $session) {
    $this->session = $session;
  }

  /**
   * @param RequestStack $requestStack
   */
  public function setRequestStack(RequestStack $requestStack) {
    $this->requestStack = $requestStack;
  }

  /**
   * @param PathValidator $pathValidator
   */
  public function setPathValidator(PathValidator $pathValidator) {
    $this->pathValidator = $pathValidator;
  }

  public function synchronizeUserAttributes(UserInterface $account, $skip_save = FALSE) {
    // Skip user authentication.
  }

  /**
   * Set login and logout destinations in user´s session.
   */
  public function setPostLoginLogoutDestination() {
    // Ensure that session is started
    if (!$this->session->isStarted()) {
      $this->session->start();
    }

    // We default at least to our frontpage
    $url = new Url('<front>');

    // If we can catch the referrer, use that
    $referer = $this->requestStack->getCurrentRequest()->server->get('HTTP_REFERER');
    if ($referer) {
      if ($valid_url = $this->pathValidator->getUrlIfValid($referer)) {
        $url = $valid_url;
      }
    }

    // In conjunction with "Redirect to login" module, it sets the current URI
    // when it triggers login. We may catch the URI from the cookie.
    if ($this->requestStack->getCurrentRequest()->cookies->has(self::COOKIE_SAML_REDIRECT)) {
      $cookie_url = unserialize($this->requestStack->getCurrentRequest()->cookies->get(self::COOKIE_SAML_REDIRECT));
      if ($valid_url = $this->pathValidator->getUrlIfValid($cookie_url)) {
        $url = $valid_url;
      }
    }

    // Store the serialized URL into session.
    $this->session->set(self::SESS_VALUE_KEY, serialize($url));
    $this->session->save();
  }

  /**
   * Get login and logout destinations in user´s session.
   *
   * @return Url|null
   */
  public function getPostLoginLogoutDestination() {
    if (!empty($this->session->get(self::SESS_VALUE_KEY))) {
      return unserialize($this->session->get(self::SESS_VALUE_KEY));
    }
    return NULL;
  }

  /**
   * Removes post login/logout destination from existing session. Nothing is
   * done if request has no session.
   */
  public function removePostLoginLogoutDestination() {
    $this->store->delete(self::SESS_VALUE_KEY);
  }

  /**
   * {@inheritdoc}
   */
  public function getPostLoginDestination() {
    return $this->getPostLoginLogoutDestination();
  }

  /**
   * {@inheritdoc}
   */
  public function getPostLogoutDestination() {
    return $this->getPostLoginLogoutDestination();
  }

  /**
   * Processes a SAML response (Assertion Consumer Service).
   *
   * First checks whether the SAML request is OK, then takes action on the
   * Drupal user (logs in / maps existing / create new) depending on attributes
   * sent in the request and our module configuration.
   *
   * @throws Exception
   */
  public function acs() {
    // This call can either set an error condition or throw a
    // \OneLogin_Saml2_Error exception, depending on whether or not we are
    // processing a POST request. Don't catch the exception.
    $this->getSamlAuth()->processResponse();
    // Now look if there were any errors and also throw.
    $errors = $this->getSamlAuth()->getErrors();
    if (!empty($errors)) {
      // We have one or multiple error types / short descriptions, and one
      // 'reason' for the last error.
      throw new RuntimeException('Error(s) encountered during processing of ACS response. Type(s): ' . implode(', ', array_unique($errors)) . '; reason given for last error: ' . $
    }

    if (!$this->isAuthenticated()) {
      throw new RuntimeException('Could not authenticate.');
    }

    $cookie_name = new Cookie(self::COOKIE_SAML_NAME, $this->getAttributeByConfig('user_name_attribute'));
    $cookie_mail = new Cookie(self::COOKIE_SAML_EMAIL, $this->getAttributeByConfig('user_mail_attribute'));
    $cookie_username = new Cookie(self::COOKIE_SAML_USER, $this->getAttributeByConfig('user_username_attribute'));

    if ($this->requestStack->getCurrentRequest()->cookies->has(self::COOKIE_SAML_REDIRECT)) {
      $cookie_url = $this->requestStack->getCurrentRequest()->cookies->get(self::COOKIE_SAML_REDIRECT);
      if ($valid_url = $this->pathValidator->getUrlIfValid($cookie_url)) {
        $url = $valid_url;
      }
    }

    $response = new RedirectResponse($url);
    $response->headers->setCookie($cookie_name);
    $response->headers->setCookie($cookie_mail);
    $response->headers->setCookie($cookie_username);

    // Add the $url object as a dependency of whatever you're returning. Probably a response.
    return $response;

    //    $this->saml->getPostLoginDestination()->toString();
//    $response = new TrustedRedirectResponse($url);
//    $response->addCacheableDependency($url);
//    $this->saml->removePostLoginLogoutDestination();
  }

  /**
   * {@inheritdoc}
   */
  public function logout($return_to = null) {
    if (!$return_to) {
      $sp_config = $this->samlAuth->getSettings()->getSPData();
      $return_to = $sp_config['singleLogoutService']['url'];
    }

    // Get logout return URL
    $parameters = ['referrer' => $return_to];
    if ($return_url = $this->getPostLogoutDestination()) {
      $parameters['return'] = $return_url->setAbsolute(TRUE)->toString(TRUE)->getGeneratedUrl();
    }

    $this->samlAuth->logout($return_to, $parameters);
  }

}
