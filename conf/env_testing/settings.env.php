<?php

/**
 * Environment specific settings.
 */

// Database configuration for the project.
$databases['default']['default'] = array (
    'database' => 'mildred1db',
    'username' => 'mildred1',
    'password' => 'cdvowrUPQUBZZ7Ny7Ep8',
    'prefix' => '',
    'host' => '192.168.3.170',
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
