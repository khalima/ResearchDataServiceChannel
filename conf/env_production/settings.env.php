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
 * Configure SAML Authentication module.
 */
$config['samlauth.authentication']['drupal_saml_login'] = FALSE;
$config['samlauth.authentication']['sp_entity_id'] = 'https://datasupport.helsinki.fi/samlauth';
$config['samlauth.authentication']['sp_name_id_format'] = 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient';
$config['samlauth.authentication']['sp_cert_type'] = 'folder';
$config['samlauth.authentication']['sp_cert_folder'] = '/data/rds/shared';
$config['samlauth.authentication']['sp_x509_certificate'] = '';
$config['samlauth.authentication']['sp_private_key'] = '';
$config['samlauth.authentication']['idp_entity_id'] = 'https://login.helsinki.fi/shibboleth';
$config['samlauth.authentication']['idp_single_sign_on_service'] = 'https://login.helsinki.fi/idp/profile/SAML2/Redirect/SSO';
$config['samlauth.authentication']['idp_single_log_out_service'] = 'https://login.helsinki.fi/idp/logout.jsp';
$config['samlauth.authentication']['idp_change_password_service'] = 'https://www.helsinki.fi/salasana';
$config['samlauth.authentication']['idp_x509_certificate'] = 'MIIFnDCCBISgAwIBAgIQaa2rCkgYcvukeleEtUvyozANBgkqhkiG9w0BAQsFADBk MQswCQYDVQQGEwJOTDEWMBQGA1UECBMNTm9vcmQtSG9sbGFuZDESMBAGA1UEBxMJ QW1zdGVyZGFtMQ8wDQYDVQQKEwZURVJFTkExGDAWBgNVBAMTD1RFUkVOQSBTU0wg Q0EgMjAeFw0xNTA0MjcwMDAwMDBaFw0xODA2MTMyMzU5NTlaMD8xITAfBgNVBAsT GERvbWFpbiBDb250cm9sIFZhbGlkYXRlZDEaMBgGA1UEAxMRbG9naW4uaGVsc2lu a2kuZmkwggIiMA0GCSqGSIb3DQEBAQUAA4ICDwAwggIKAoICAQC9+9NToC10zJ71 Akre2PbHsI2Qzpdi0vFdlDh9W7BtFqz/4fpX/xe92EaGpDIxS06gSjl1XB4jGjbq 6YUVXbu9Tg/FxGxfGt/lsEyvHrzyt3EdDQlm2Tn+WVwe5CDbLKsxAeC2nKUJPgdc 8KT5iyKUAfyU/XK3TgBn0zuyM9XrI/C2bQfNgCF9KfrJALIUI6/Y+s8VCHTzQnwy NEZPdTtnb/8XNhKbf+UH42vGjRWH6loRN/6ZLg7t0FICvS73CtBxlh2IA6Qr0fNf ThgeLr7R3IuY7Mff8Fpg1N/3uy85Pi79Lsfo6dbY0CAolgVvFKtZQVh4U1LzpvuF RttfOqMCruEgTA+3LUkBbQCY0bU43AqDByDHDnw+CU8+ivjw99OI2292cLVd1cFC Y6Wx1fBgJps+d85fWFsz9kppYeAv7PLbMEesj4OPXGYAF84bBmrdNwcl0SoAVfzZ WTAAJZdxGAKsSpEdokQu7gsAXegLTFGCrSkxI/9337ZeMLd6LJyrWYxXFmxk6R5u owmZwKHxXOpq37ZlJksnCAghvoNjqdA0WBQeXNCrwrScWIdQIWBJCLYj/pYIBckZ Axvjm21cAgLl98lnCtWG3HG2t4GykxCsgtdY8hPw0Ze/oXBZpTY78DKD0pOW6ndj WGgtjZWiW6yrj8mlV8hWqCULfS/rfwIDAQABo4IBbTCCAWkwHwYDVR0jBBgwFoAU W9CKHJoyW+C13ZZUG+GGKLD9tr0wHQYDVR0OBBYEFMqg7/cYdb2o9CSfG4wtgFrc gwH9MA4GA1UdDwEB/wQEAwIFoDAMBgNVHRMBAf8EAjAAMB0GA1UdJQQWMBQGCCsG AQUFBwMBBggrBgEFBQcDAjAiBgNVHSAEGzAZMA0GCysGAQQBsjEBAgIdMAgGBmeB DAECATA6BgNVHR8EMzAxMC+gLaArhilodHRwOi8vY3JsLnVzZXJ0cnVzdC5jb20v VEVSRU5BU1NMQ0EyLmNybDBsBggrBgEFBQcBAQRgMF4wNQYIKwYBBQUHMAKGKWh0 dHA6Ly9jcnQudXNlcnRydXN0LmNvbS9URVJFTkFTU0xDQTIuY3J0MCUGCCsGAQUF BzABhhlodHRwOi8vb2NzcC51c2VydHJ1c3QuY29tMBwGA1UdEQQVMBOCEWxvZ2lu LmhlbHNpbmtpLmZpMA0GCSqGSIb3DQEBCwUAA4IBAQCJ2I4mTHMYM2SZuAf9XI2x Lk/VLU8LqZOGc5yvQCdLG3iLz6xkUU/ESG4Nrwyl8MN8IB/ul3bbZVriZ+htb4R4 CmB44srsj10lKAHcCDYbxUNj+EbewMXkXqBybjkqlih5JjdlH1eA72M8aDvtHIi9 7YOfX0qkuYX1MC17UL+IlD1Ed5oSdckyPUCDrTksNmZ3AegQVJlDmk8KL7xXm6qZ 1JFj+dX1uM1cPa0sf0ZK6fI8aOhPhKz+usclFplwR7ohnEp60S3KgH9VbhHpjItj DjLzl9rFbErZuexB3N7XJhesfd5IwneNaJ2oVjJZB9gzknuG6bwrURgOA8sHcxV0';
$config['samlauth.authentication']['unique_id_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.1';
$config['samlauth.authentication']['map_users'] = FALSE;
$config['samlauth.authentication']['map_users_email'] = '';
$config['samlauth.authentication']['create_users'] = TRUE;
$config['samlauth.authentication']['user_name_attribute'] = 'urn:oid:2.5.4.3';
$config['samlauth.authentication']['user_mail_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.3';
$config['samlauth.authentication']['user_username_attribute'] = 'urn:oid:0.9.2342.19200300.100.1.1';
$config['samlauth.authentication']['security_authn_requests_sign'] = TRUE;
$config['samlauth.authentication']['security_messages_sign'] = TRUE;
$config['samlauth.authentication']['security_name_id_sign'] = FALSE;
$config['samlauth.authentication']['security_request_authn_context'] = FALSE;

/**
 * ESB API endpoint settings.
 */
$config['esb']['url'] = 'https://esbpub2.it.helsinki.fi/devel/mildred/createticket';
//$config['esb']['url'] = 'https://esbpub1.it.helsinki.fi/mildred/createticket';
