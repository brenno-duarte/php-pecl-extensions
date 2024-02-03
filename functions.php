<?php

require_once 'vendor/autoload.php';

require_once 'constants.php';
require_once 'oauth.php';
require_once 'simdjson.php';
require_once 'ssdeep.php';
require_once 'statistic.php';
require_once 'xdiff.php';
require_once 'yaml.php';

if (!function_exists('var_representation')) {
    /**
     * Convert a variable to a string in a way that fixes the shortcomings of `var_export()`.
     *
     * @param mixed $value
     * @param int $flags bitmask of flags (VAR_REPRESENTATION_SINGLE_LINE, VAR_REPRESENTATION_UNESCAPED)
     * @suppress PhanRedefineFunctionInternal this is a polyfill
     */
    function var_representation($value, int $flags = 0): string
    {
        return PeclPolyfill\VarRepresentation\Encoder::toVarRepresentation($value, $flags);
    }
}

if (!extension_loaded('xxtea')) {
    // public functions
    // $str is the string to be encrypted.
    // $key is the encrypt key. It is the same as the decrypt key.
    function xxtea_encrypt(string $str, string $key)
    {
        return PeclPolyfill\XXTEA\XXTEA::encrypt($str, $key);
    }

    // $str is the string to be decrypted.
    // $key is the decrypt key. It is the same as the encrypt key.
    function xxtea_decrypt(string $str, string $key)
    {
        return PeclPolyfill\XXTEA\XXTEA::decrypt($str, $key);
    }
}

if (!extension_loaded('igbinary_serialize')) {
    function igbinary_serialize(mixed $value)
    {
        return serialize($value);
    }
}

if (!extension_loaded('igbinary_unserialize')) {
    function igbinary_unserialize(mixed $value)
    {
        return unserialize($value);
    }
}
