<?php

namespace PeclPolyfill\Xdiff;

use DiffMatchPatch\DiffMatchPatch;
use PeclPolyfill\Xdiff\Merge\AbstractMergeBase;
use PeclPolyfill\Xdiff\Merge\Hunk;
use PeclPolyfill\Xdiff\Merge\Line;
use PeclPolyfill\Xdiff\Merge\Merge;
use SebastianBergmann\Diff\Differ;

class Xdiff extends AbstractMergeBase
{
    //private static DiffMatchPatch $diff;

    private static function getInstance()
    {
        return new DiffMatchPatch();
    }

    public static function xdiffStringDiff(string $old_data, string $new_data): string
    {
        $diff = self::getInstance();
        return $diff->patch_toText($diff->patch_make($old_data, $new_data));
    }

    public static function xdiffStringPatch(string $str, string $patch): array
    {
        $diff = self::getInstance();
        return $diff->patch_apply($diff->patch_fromText($patch), $str)[0] ?? '';
    }

    public static function xdiffFileMerge3(
        string $old_file,
        string $new_file1,
        string $new_file2,
        string $dest
    ): mixed {
        $merge = new Merge();
        $file = $merge->merge($old_file, $new_file1, $new_file2);

        if (is_array($file)) {
            return $file;
        }

        $res = file_put_contents($dest, $file);

        if ($res != false) {
            return true;
        }
    }

    public static function xdiffFileDiff(string $old_file, string $new_file, string $dest)
    {
        $final = null;
        $file = Diff::compareFiles($old_file, $new_file);

        foreach ($file as $value) {
            //$final = implode(PHP_EOL, $value);
            var_dump($value);
        }

        exit;
        
        return $final;
    }
}
