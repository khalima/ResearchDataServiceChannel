<?php

namespace Drupal\hy_samlauth\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\hy_samlauth\Event\HySamlauthEvents;
use Drupal\hy_samlauth\Event\HySamlauthUserSyncEvent;
use Egulias\EmailValidator\EmailValidator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\PrivateTempStoreFactory;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Event subscriber that synchronizes user properties on a user_sync event.
 *
 * This is basic module functionality, partially driven by config options. It's
 * split out into an event subscriber so that the logic is easier to tweak for
 * individual sites. (Set message or not? Completely break off login if an
 * account with the same name is found, or continue with a non-renamed account?
 * etc.)
 */
class UserSyncEventSubscriber implements EventSubscriberInterface {

  const USER_SYNC = 'hy_samlauth.user_sync';
  const USER_EMAIL = 'UH_RDS_EMAIL';
  const USER_NAME = 'UH_RDS_NAME';

  /**
   * The EntityTypeManager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The typed data manager.
   *
   * @var \Drupal\Core\TypedData\TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * The email validator.
   *
   * @var \Egulias\EmailValidator\EmailValidator
   */
  protected $emailValidator;

  /**
   * A configuration object containing samlauth settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;
  protected $requestStack;

  /**
   * Construct a new SamlauthUserSyncSubscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManager service.
   * @param \Egulias\EmailValidator\EmailValidator $email_validator
   *   The email validator.
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typed_data_manager
   *   The typed data manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, TypedDataManagerInterface $typed_data_manager, EmailValidator $email_validator, RequestStack $requestStack) {
    $this->entityTypeManager = $entity_type_manager;
    $this->emailValidator = $email_validator;
    $this->typedDataManager = $typed_data_manager;
    $this->config = $config_factory->get('samlauth.authentication');
    $this->requestStack = $requestStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[HySamlauthUserSyncEvent::USER_COOKIE][] = ['onUserSync'];
    return $events;
  }

  /**
   * Performs actions to synchronize users with Factory data on login.
   *
   * @param \Drupal\samlauth\Event\SamlauthUserSyncEvent $event
   *   The event.
   */
  public function onUserSync(HySamlauthUserSyncEvent $event) {
    $session = $event->getSession();
    print_r($session); 
    $this->requestStack->getCurrentRequest()->cookies->set(self::USER_NAME, $this->getAttributeByConfig('cn', $event));
    $this->requestStack->getCurrentRequest()->cookies->set(self::USER_EMAIL, $this->getAttributeByConfig('mail', $event));
    $this->requestStack->getCurrentRequest()->cookies->set('TEST-NAME', $this->getAttributeByConfig('urn:oid:0.9.2342.19200300.100.1.1', $event));
    $this->requestStack->getCurrentRequest()->cookies->set('TEST-EMAIL', $this->getAttributeByConfig('urn:oid:0.9.2342.19200300.100.1.3', $event));
  }

  /**
   * Returns value from a SAML attribute whose name is configured in our module.
   *
   * This is suitable for single-value attributes. (Most values are.)
   *
   * @param string $config_key
   *   A key in the module's configuration, containing the name of a SAML
   *   attribute.
   * @param \Drupal\samlauth\Event\SamlauthUserSyncEvent $event
   *   The event, which holds the attributes from the SAML response.
   *
   * @return mixed|null
   *   The SAML attribute value; NULL if the attribute value was not found.
   */
  public function getAttributeByConfig($config_key, HySamlauthUserSyncEvent $event) {
    $attributes = $event->getAttributes();
    $attribute_name = $this->config->get($config_key);
    return $attribute_name && !empty($attributes[$attribute_name][0]) ? $attributes[$attribute_name][0] : NULL;
  }

}
