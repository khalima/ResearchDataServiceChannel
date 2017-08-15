<?php

/**
 * Environment specific settings.
 */

// Database configuration for the project.
$databases['default']['default'] = array (
    'database' => 'otava_lcms',
    'username' => 'otava',
    'password' => 'jUBnxiTu0VrK',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
);

/**
 * Environment indication.
 */
$config['environment']['env'] = 'testing';

$settings['trusted_host_patterns'] = array(
    '^otava-lcms-test\.druid\.fi',
);
