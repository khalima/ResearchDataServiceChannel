<?php

namespace Drupal\hy_samlauth\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Replace saml login with own implementation that has additional features.
    $controller_replacements = [
      'samlauth.saml_controller_login' => 'Drupal\\hy_samlauth\\Controller\\SamlController::login',
      'samlauth.saml_controller_logout' => 'Drupal\\hy_samlauth\\Controller\\SamlController::logout',
      'samlauth.saml_controller_acs' => 'Drupal\\hy_samlauth\\Controller\\SamlController::acs',
      'samlauth.saml_controller_sls' => 'Drupal\\hy_samlauth\\Controller\\SamlController::sls',
    ];
    foreach ($controller_replacements as $route_name => $controller) {
      if ($route = $collection->get($route_name)) {
        $route->setDefault('_controller', $controller);
      }
    }
  }
}
