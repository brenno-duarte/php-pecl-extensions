<?php

require_once 'vendor/autoload.php';

use PeclPolyfill\Ssdeep\{SequenceMatcher, Utils};

if (!function_exists('ssdeep_fuzzy_compare')) {
    function ssdeep_fuzzy_compare(string $signature1, string $signature2)
    {
        if (strlen($signature1) === 0 || strlen($signature2) === 0) {
            return 0;
        }

        $m = new SequenceMatcher($signature1, $signature2);

        return Utils::intr(100 * $m->Ratio());
    }
}

if (!function_exists('ssdeep_fuzzy_hash')) {
    function ssdeep_fuzzy_hash(string $to_hash)
    {
        $secret_key = md5($to_hash);
        return hash_hmac('sha256', $to_hash, $secret_key);
    }
}

if (!function_exists('ssdeep_fuzzy_hash_filename')) {
    function ssdeep_fuzzy_hash_filename(string $file_name)
    {
        $file_name = str_replace('/', DIRECTORY_SEPARATOR, $file_name);

        if (!is_file($file_name)) {
            throw new \Exception($file_name . " isn't a file");
        }

        $to_hash = basename($file_name);
        $secret_key = md5($to_hash);
        return hash_hmac('sha256', $to_hash, $secret_key);
    }
}
