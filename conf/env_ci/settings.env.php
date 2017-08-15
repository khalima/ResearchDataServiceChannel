<?php

/**
 * CI-specific settings.
 */

$databases['default']['default'] = array (
  'database' => 'd8template',
  'username' => 'root',
  'password' => NULL,
  'prefix' => '',
  'host' => '127.0.0.1',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$config['system.logging']['error_level'] = 'verbose';

$config['environment']['env'] = 'ci';
