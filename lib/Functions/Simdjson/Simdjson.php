<?php

declare(strict_types=1);

namespace PeclPolyfill\Functions\Simdjson;

final class Simdjson
{
    public const DEFAULT_DEPTH = 512;
    private const DEFAULT_OPTIONS = \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR;

    public static function simdjsonDecode(string $json, bool $assoc = false, int $depth = self::DEFAULT_DEPTH)
    {
        return \json_decode($json, $assoc, $depth, self::DEFAULT_OPTIONS);
    }

    public static function simdjsonIsValid(string $json, int $depth = self::DEFAULT_DEPTH): bool
    {
        try {
            \json_decode($json, false, $depth, self::DEFAULT_OPTIONS);
        } catch (SimdJsonException) {
            return false;
        }

        return true;
    }

    public static function simdjsonKeyValue(
        string $json,
        string $key,
        bool $assoc = false,
        int $depth = self::DEFAULT_DEPTH
    ) {
        self::checkDepth($depth);
        $valid = self::simdjsonIsValid($json, $depth);

        if ($valid == false) {
            throw new SimdjsonException("JSON not valid");
        }

        $data = self::simdjsonDecode($json, $assoc, $depth);

        if ($assoc === true) {
            $pathValue = self::keyValueArray($key, $data);
        } else {
            $pathValue = self::keyValueObject($key, $data);
        }

        return $pathValue;
    }

    public static function simdjsonKeyExists(string $json, string $key, int $depth = self::DEFAULT_DEPTH): bool
    {
        self::checkDepth($depth);
        $data = self::simdjsonDecode($json, true, $depth);

        try {
            self::keyValueArray($key, $data);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function simdjsonKeyCount(string $json, string $key, int $depth = self::DEFAULT_DEPTH, bool $throw_if_uncountable = false): int
    {
        self::checkDepth($depth);
        $value = self::simdjsonKeyValue($json, $key, true, $depth);

        if (!is_countable($value) && $throw_if_uncountable == true) {
            throw new SimdjsonException("JSON is not countable");
        }

        return \count($value);
    }

    private static function keyValueArray(string $path, array $data)
    {
        $pathKeys = \explode('/', $path);

        foreach ($pathKeys as $pathKey) {
            if (!isset($data[$pathKey])) {
                throw new SimdJsonException(\sprintf('Key "%s" not found for path "%s".', $pathKey, $path));
            }

            $data = $data[$pathKey];
        }

        return $data;
    }

    private static function keyValueObject(string $path, \stdClass $data)
    {
        $pathKeys = \explode('/', $path);
        $copy = $data;

        foreach ($pathKeys as $pathKey) {
            if (\is_array($copy)) {
                if (!isset($copy[$pathKey])) {
                    throw new SimdJsonException(\sprintf('Key "%s" not found for path "%s".', $pathKey, $path));
                }
                $copy = $copy[$pathKey];
            } elseif (!isset($copy->{$pathKey})) {
                throw new SimdJsonException(\sprintf('Key "%s" not found for path "%s".', $pathKey, $path));
            } else {
                $copy = $copy->{$pathKey};
            }
        }

        return $copy;
    }

    private static function checkDepth(int $depth): void
    {
        if ($depth <= 0) {
            throw new SimdJsonValueError("Depth is invalid");
        }

        if (!is_numeric($depth)) {
            throw new SimdJsonValueError("Depth is not numeric");
        }

        if ($depth > 1000) {
            throw new SimdJsonValueError("Depth is too large");
        }
    }
}
