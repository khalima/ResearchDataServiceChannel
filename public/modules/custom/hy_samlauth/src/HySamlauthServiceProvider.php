<?php

namespace Drupal\hy_samlauth;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modify the SamlAuth service with an overridden/extended service.
 */
class HySamlauthServiceProvider extends ServiceProviderBase {
  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('samlauth.saml');
    $definition->setClass('Drupal\hy_samlauth\SamlService');
    $definition->addArgument(new Reference('request_stack'));
    $definition->addArgument(new Reference('session'));
    $definition->addArgument(new Reference('path.validator'));

    $user_sync_definition = $container->getDefinition('samlauth.event_subscriber.user_sync');
    $user_sync_definition->setClass('Drupal\hy_samlauth\EventSubscriber\UserSyncEventSubscriber');
    $user_sync_definition->addArgument(new Reference('config.factory'));
    $user_sync_definition->addArgument(new Reference('entity_type.manager'));
    $user_sync_definition->addArgument(new Reference('typed_data_manager'));
    $user_sync_definition->addArgument(new Reference('email.validator'));
    $user_sync_definition->addArgument(new Reference('user.private_tempstore'));
    $user_sync_definition->addArgument(new Reference('session_manager'));
    $user_sync_definition->addArgument(new Reference('current_user'));
  }
}
