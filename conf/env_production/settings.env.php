<?php

/**
 * Environment specific settings.
 */

/**
 * Environment indication.
 */
$config['environment']['env'] = 'production';

$settings['hash_salt'] = 'bvZQQ28Qq1xxTW7uEND-XWGIKso9tClM0nzWJ8xoYM9cG0j_a_mlH8thumoZiGDkRHIpPKCHGQ';

$settings['trusted_host_patterns'] = array(
  '^datasupport\.helsinki\.fi',
);

/**
 * ESB API endpoint settings.
 */
$config['esb']['url'] = 'https://esbpub2.it.helsinki.fi/devel/mildred/createticket';
//$config['esb']['url'] = 'https://esbpub1.it.helsinki.fi/mildred/createticket';
