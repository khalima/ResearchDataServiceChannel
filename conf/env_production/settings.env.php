<?php

/**
 * Environment specific settings.
 */

// Database configuration for the project.
$databases['default']['default'] = array (
    'database' => 'rds_db',
    'username' => 'rds_user',
    'password' => 'changethistocorrectone',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
);

/**
 * Environment indication.
 */
$config['environment']['env'] = 'production';

$settings['hash_salt'] = 'bvZQQ28Qq1xxTW7uEND-XWGIKso9tClM0nzWJ8xoYM9cG0j_a_mlH8thumoZiGDkRHIpPKCHGQ';

//$settings['reverse_proxy'] = TRUE;
//$settings['reverse_proxy_addresses'] = array($_SERVER['REMOTE_ADDR']);

$settings['trusted_host_patterns'] = array(
    '^datasupport\.helsinki\.fi',
);

/**
 * Configure SAML Authentication module.
 */
$config['samlauth.authentication']['drupal_saml_login'] = FALSE;
$config['samlauth.authentication']['sp_entity_id'] = 'https://datasupport.helsinki.fi';
$config['samlauth.authentication']['sp_name_id_format'] = 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient';
$config['samlauth.authentication']['sp_cert_type'] = 'folder';
$config['samlauth.authentication']['sp_cert_folder'] = '/var/www/saml';
$config['samlauth.authentication']['sp_x509_certificate'] = '';
$config['samlauth.authentication']['sp_private_key'] = '';
$config['samlauth.authentication']['idp_entity_id'] = 'https://login.helsinki.fi/shibboleth';
$config['samlauth.authentication']['idp_single_sign_on_service'] = 'https://login.helsinki.fi/idp/profile/SAML2/Redirect/SSO';
$config['samlauth.authentication']['idp_single_log_out_service'] = 'https://login.helsinki.fi/idp/logout.jsp';
$config['samlauth.authentication']['idp_change_password_service'] = 'http://www.helsinki.fi/salasana';
$config['samlauth.authentication']['idp_x509_certificate'] = 'sertin sisältö';
$config['samlauth.authentication']['unique_id_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.1';
$config['samlauth.authentication']['map_users'] = FALSE;
$config['samlauth.authentication']['map_users_email'] = '';
$config['samlauth.authentication']['create_users'] = TRUE;
$config['samlauth.authentication']['user_name_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.1';
$config['samlauth.authentication']['user_mail_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.3';
$config['samlauth.authentication']['security_authn_requests_sign'] = TRUE;
$config['samlauth.authentication']['security_messages_sign'] = TRUE;
$config['samlauth.authentication']['security_name_id_sign'] = FALSE;
$config['samlauth.authentication']['security_request_authn_context'] = FALSE;
