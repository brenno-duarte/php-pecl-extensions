<?php

declare(strict_types=1);

namespace PeclPolyfill\Functions\Xdiff\Merge;

abstract class AbstractMergeBase
{
    /**
     * Merge obvious cases when only one text changes..
     *
     * @param string $base
     *   The original text.
     * @param string $remote
     *   The first variant text.
     * @param string $local
     *   The second variant text.
     *
     * @return string|null
     *   The merge result or null if the merge is not obvious.
     */
    protected static function simpleMerge(string $base, string $remote, string $local)
    {
        // Skip complex merging if there is nothing to do.
        if ($base === $remote) {
            return $local;
        }
        if ($base === $local) {
            return $remote;
        }
        if ($remote === $local) {
            return $remote;
        }
        // Return nothing and let sub-classes deal with it.
        return null;
    }

    /**
     * Split it line-by-line.
     *
     * @param string $input
     *
     * @return array
     */
    protected static function splitStringByLines(string $input): array
    {
        return \preg_split('/(.*\R)/', $input, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }
}
