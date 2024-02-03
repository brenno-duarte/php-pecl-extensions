<?php

require_once 'vendor/autoload.php';

use PeclPolyfill\Xdiff\Xdiff;

if (!function_exists('xdiff_string_diff')) {
    function xdiff_string_diff(string $old_data, string $new_data)
    {
        return Xdiff::xdiffStringDiff($old_data, $new_data);
    }
}

if (!function_exists('xdiff_string_patch')) {
    function xdiff_string_patch(string $str, string $patch)
    {
        return Xdiff::xdiffStringPatch($str, $patch);
    }
}

if (!function_exists('xdiff_file_merge3')) {
    function xdiff_file_merge3(string $old_file, string $new_file1, string $new_file2, string $dest)
    {
        return Xdiff::xdiffFileMerge3($old_file, $new_file1, $new_file2, $dest);
    }
}

if (!function_exists('xdiff_file_diff')) {
    function xdiff_file_diff(string $old_file, string $new_file, string $dest)
    {
        return Xdiff::xdiffFileDiff($old_file, $new_file, $dest);
    }
}
