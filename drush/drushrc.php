<?php

//$options['uri'] = "http://mysite.local";

// Test shell-alias, use "drush foobar"
$options['shell-aliases']['foobar'] = '!drush sa';

// Sync database from remote to local
$options['shell-aliases']['sync-db'] = '!drush sql-sync @testing @self';

// Sync files folder from remote to local
$options['shell-aliases']['sync-files'] = '!drush rsync --mode=Pakzu --exclude=css --exclude=js @production:%files @self:%files';

if (getenv('AMAZEEIO_BASE_URL')) {
  $options['uri'] = getenv('AMAZEEIO_BASE_URL');
}
