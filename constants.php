<?php

/**
 * OAUTH
 */
if (!function_exists('oauth_get_sbs')) {
    define("OAUTH_USER_AGENT", "PECL-OAuth/0.1-unpecl");
    define("OAUTH_MAX_HEADER_LEN", 512);

    define("OAUTH_AUTH_TYPE_URI", 0x01);
    define("OAUTH_AUTH_TYPE_FORM", 0x02);
    define("OAUTH_AUTH_TYPE_AUTHORIZATION", 0x03);
    define("OAUTH_AUTH_TYPE_NONE", 0x04);

    define("OAUTH_SIG_METHOD_HMACSHA1", "HMAC-SHA1");
    define("OAUTH_SIG_METHOD_HMACSHA256", "HMAC-SHA256");
    define("OAUTH_SIG_METHOD_RSASHA1", "RSA-SHA1");
    define("OAUTH_SIG_METHOD_PLAINTEXT", "PLAINTEXT");

    define("OAUTH_HTTP_METHOD_GET", "GET");
    define("OAUTH_HTTP_METHOD_POST", "POST");
    define("OAUTH_HTTP_METHOD_PUT", "PUT");
    define("OAUTH_HTTP_METHOD_HEAD", "HEAD");
    define("OAUTH_HTTP_METHOD_DELETE", "DELETE");

    define("OAUTH_REQENGINE_STREAMS", 1);
    define("OAUTH_REQENGINE_CURL", 2);

    define("OAUTH_SSLCHECK_NONE", 0x00);
    define("OAUTH_SSLCHECK_HOST", 0x01);
    define("OAUTH_SSLCHECK_PEER", 0x02);
    define("OAUTH_SSLCHECK_BOTH", 0x03);
}

/**
 * SSDEEP
 */
if (!defined('PHP_SSDEEP_VERSION')) {
    define('PHP_SSDEEP_VERSION', '1.0.4"');
    define('PHP_SSDEEP_EXTNAME', 'ssdeep');
}

/**
 * VAR REPRESENTATION
 */
if (!defined('VAR_REPRESENTATION_SINGLE_LINE')) {
    define('VAR_REPRESENTATION_SINGLE_LINE', 1);
}
if (!defined('VAR_REPRESENTATION_UNESCAPED')) {
    define('VAR_REPRESENTATION_UNESCAPED', 2);
}