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
  }
}
