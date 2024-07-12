<?php

require_once 'vendor/autoload.php';

require_once 'constants.php';
require_once 'inotify.php';
require_once 'oauth.php';
require_once 'statistic.php';

use PeclPolyfill\Functions\{Base58\Base58, Scrypt\Scrypt, Simdjson\Simdjson, Xdiff\Xdiff};
use PeclPolyfill\Functions\Ssdeep\{SequenceMatcher, Utils};
use PeclPolyfill\Functions\YAML\{YAML, YamlException};

/**
 * VAR_REPRESENTATION
 */
if (!extension_loaded('var_representation')) {
    /**
     * Convert a variable to a string in a way that fixes the shortcomings of `var_export()`.
     *
     * @param mixed $value
     * @param int $flags bitmask of flags (VAR_REPRESENTATION_SINGLE_LINE, VAR_REPRESENTATION_UNESCAPED)
     * @suppress PhanRedefineFunctionInternal this is a polyfill
     */
    function var_representation($value, int $flags = 0): string
    {
        return PeclPolyfill\Functions\VarRepresentation\Encoder::toVarRepresentation($value, $flags);
    }
}

/**
 * XXTEA -----------------------------------------------------------------------------------
 */
if (!extension_loaded('xxtea')) {
    // public functions
    // $str is the string to be encrypted.
    // $key is the encrypt key. It is the same as the decrypt key.
    function xxtea_encrypt(string $str, string $key)
    {
        return PeclPolyfill\Functions\XXTEA\XXTEA::encrypt($str, $key);
    }

    // $str is the string to be decrypted.
    // $key is the decrypt key. It is the same as the encrypt key.
    function xxtea_decrypt(string $str, string $key)
    {
        return PeclPolyfill\Functions\XXTEA\XXTEA::decrypt($str, $key);
    }
}

/**
 * SSDEEP -----------------------------------------------------------------------------------
 */
if (!extension_loaded('ssdeep')) {
    /**
     * Calculates the match score between two fuzzy hash signatures
     *
     * @param string $signature1
     * @param string $signature2
     * 
     * @return int
     */
    function ssdeep_fuzzy_compare(string $signature1, string $signature2): int
    {
        if (strlen($signature1) === 0 || strlen($signature2) === 0) {
            return 0;
        }

        $m = new SequenceMatcher($signature1, $signature2);

        return Utils::intr(100 * $m->Ratio());
    }

    /**
     * Create a fuzzy hash from a string
     *
     * @param string $to_hash
     * 
     * @return string
     */
    function ssdeep_fuzzy_hash(string $to_hash): string
    {
        $secret_key = md5($to_hash);
        return hash_hmac('sha256', $to_hash, $secret_key);
    }

    /**
     * Create a fuzzy hash from a file
     *
     * @param string $file_name
     * 
     * @return string
     */
    function ssdeep_fuzzy_hash_filename(string $file_name): string
    {
        $file_name = str_replace('/', DIRECTORY_SEPARATOR, $file_name);

        if (!is_file($file_name)) {
            throw new \Exception($file_name . " isn't a file");
        }

        $to_hash = basename($file_name);
        return ssdeep_fuzzy_hash($to_hash);
    }
}

/**
 * YAML -----------------------------------------------------------------------------------
 */
if (!extension_loaded('yaml')) {
    /**
     * Parse a YAML stream
     *
     * @param string $input
     * 
     * @return mixed
     */
    function yaml_parse(string $input): mixed
    {
        if (is_file($input)) {
            throw new YamlException("File found. Use \"yaml_parse_file\"");
        }

        return YAML::YAMLLoad($input);
    }

    /**
     * Parse a YAML stream from a file
     *
     * @param string $input
     * 
     * @return mixed
     */
    function yaml_parse_file(string $input): mixed
    {
        if (!is_file($input)) {
            throw new YamlException("String found. Use \"yaml_parse\"");
        }

        return YAML::YAMLLoad($input);
    }

    /**
     * Returns the YAML representation of a value
     *
     * @param array $input
     * 
     * @return mixed
     */
    function yaml_emit(array $input): mixed
    {
        return YAML::YAMLDump($input);
    }
}

/**
 * BASE58 -----------------------------------------------------------------------------------
 */
if (!extension_loaded('base58')) {
    function base58_encode(string $string)
    {
        $base58 = new Base58();
        return $base58->encode($string);
    }

    function base58_decode(string $string)
    {
        $base58 = new Base58();
        return $base58->decode($string);
    }
}

/**
 * XDIFF -----------------------------------------------------------------------------------
 */
if (!function_exists('xdiff')) {
    function xdiff_string_diff(string $old_data, string $new_data)
    {
        return Xdiff::xdiffStringDiff($old_data, $new_data);
    }

    function xdiff_string_patch(string $str, string $patch)
    {
        return Xdiff::xdiffStringPatch($str, $patch);
    }

    function xdiff_file_merge3(string $old_file, string $new_file1, string $new_file2, string $dest)
    {
        return Xdiff::xdiffFileMerge3($old_file, $new_file1, $new_file2, $dest);
    }

    function xdiff_file_diff(string $old_file, string $new_file, string $dest)
    {
        return Xdiff::xdiffFileDiff($old_file, $new_file, $dest);
    }
}

/**
 * SIMDJSON -----------------------------------------------------------------------------------
 */
if (!extension_loaded('simdjson')) {
    function simdjson_is_valid(string $json, int $depth = 512)
    {
        return Simdjson::simdjsonIsValid($json, $depth);
    }

    function simdjson_decode(string $json, bool $associative = false, int $depth = 512)
    {
        return Simdjson::simdjsonDecode($json, $associative, $depth);
    }

    function simdjson_key_count(string $json, string $key, int $depth = 512, bool $throw_if_uncountable = false)
    {
        return Simdjson::simdjsonKeyCount($json, $key, $depth, $throw_if_uncountable);
    }

    function simdjson_key_exists(string $json, string $key, int $depth = 512)
    {
        return Simdjson::simdjsonKeyExists($json, $key, $depth);
    }

    function simdjson_key_value(string $json, string $key, bool $associative = false, int $depth = 512)
    {
        return Simdjson::simdjsonKeyValue($json, $key, $associative, $depth);
    }
}

/**
 * SCRYPT -----------------------------------------------------------------------------------
 */
if (!extension_loaded('Scrypt')) {
    function scrypt(string $password, string $salt, int $n, int $r, int $p, int $length)
    {
        return bin2hex(Scrypt::calc($password, $salt, $n, $r, $p, $length));
    }
}

/**
 * UOPZ -----------------------------------------------------------------------------------
 */
if (!extension_loaded('uopz')) {
    function uopz_get_property(string|object $class, string $property)
    {
        $reflection = new \ReflectionClass($class);
        $name = $reflection->getProperty($property);
        return $name->name;
    }

    function uopz_get_static(string|object $class, string $property)
    {
        $reflection = new \ReflectionClass($class);
        $name = $reflection->getProperty($property);
        return $name->name;
    }
}
