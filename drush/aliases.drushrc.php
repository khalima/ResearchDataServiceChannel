<?php

/**
 * See documentation for examples.
 *
 * @link https://github.com/drush-ops/drush/blob/master/examples/example.aliases.drushrc.php
 */

/**
 * Amazee.io magic. Use only if site uses Amazee.io hosting
 */
// Don't change anything here, it's magic!
//global $aliases_stub;
//if (empty($aliases_stub)) {
//  $ch = curl_init();
//  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
//  curl_setopt($ch, CURLOPT_HEADER, 0);
//  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//  curl_setopt($ch, CURLOPT_URL, 'https://drush-alias.amazeeio.cloud/aliases.drushrc.php.stub');
//  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//  $aliases_stub = curl_exec($ch);
//  curl_close($ch);
//}
//eval($aliases_stub);

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
  'uri' => 'http://dev.researchdata-hy.com',
  'root' => '/var/www/dev.researchdata-hy.com/public',
  'remote-host' => 'dev.researchdata-hy.com',
  'remote-user' => 'centos',
);
