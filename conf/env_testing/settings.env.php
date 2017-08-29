<?php

/**
 * Environment specific settings.
 */

// Database configuration for the project.
$databases['default']['default'] = array (
    'database' => 'mildred1',
    'username' => 'mildred1',
    'password' => 'mildred1',
    'prefix' => '',
    'host' => 'mildred1',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
);

/**
 * Environment indication.
 */
$config['environment']['env'] = 'testing';

$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_addresses'] = array($_SERVER['REMOTE_ADDR']);

$settings['trusted_host_patterns'] = array(
    '^dev\.researchdata-hy\.com',
);
