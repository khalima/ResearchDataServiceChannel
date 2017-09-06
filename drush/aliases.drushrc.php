<?php

/**
 * See documentation for examples.
 *
 * @link https://github.com/drush-ops/drush/blob/master/examples/example.aliases.drushrc.php
 */

/**
 * Placeholder aliases. Remove the leading hash signs to enable.
 *
 * These are the environment names we have decided to use, please don't change
 * them.
 */
#$aliases['production'] = array(
#  'uri' => 'http://example.com',
#  'root' => '/var/www/example.com/current',
#  'remote-host' => 'example.com',
#  'remote-user' => 'root',
#);

#$aliases['staging'] = array(
#  'uri' => 'http://staging.example.com',
#  'root' => '/var/www/example.com/current',
#  'remote-host' => 'staging.example.com',
#  'remote-user' => 'root',
#);

$aliases['testing'] = array(
  'uri' => 'https://dev.researchdata-hy.com/',
  'root' => '/var/www/dev.researchdata-hy.com/public',
  'remote-host' => '192.168.103.54',
  'remote-user' => 'centos',
);
