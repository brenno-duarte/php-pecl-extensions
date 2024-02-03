<?php

require_once 'vendor/autoload.php';

use PeclPolyfill\Simdjson\Simdjson;

if (!function_exists('simdjson_is_valid')) {
    function simdjson_is_valid(string $json, int $depth = 512)
    {
        return Simdjson::simdjsonIsValid($json, $depth);
    }
}

if (!function_exists('simdjson_decode')) {
    function simdjson_decode(string $json, bool $associative = false, int $depth = 512)
    {
        return Simdjson::simdjsonDecode($json, $associative, $depth);
    }
}

if (!function_exists('simdjson_key_count')) {
    function simdjson_key_count(string $json, string $key, int $depth = 512, bool $throw_if_uncountable = false)
    {
        return Simdjson::simdjsonKeyCount($json, $key, $depth, $throw_if_uncountable);
    }
}

if (!function_exists('simdjson_key_exists')) {
    function simdjson_key_exists(string $json, string $key, int $depth = 512)
    {
        return Simdjson::simdjsonKeyExists($json, $key, $depth);
    }
}

if (!function_exists('simdjson_key_value')) {
    function simdjson_key_value(string $json, string $key, bool $associative = false, int $depth = 512)
    {
        return Simdjson::simdjsonKeyValue($json, $key, $associative, $depth);
    }
}
