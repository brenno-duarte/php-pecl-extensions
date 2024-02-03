<?php

namespace PeclPolyfill\YAML\Traits;

trait CompareTrait
{
    private function isTrueWord(mixed $value): bool
    {
        $words = self::getTranslations(['true', 'on', 'yes', 'y']);
        return in_array($value, $words, true);
    }

    private function isFalseWord(mixed $value): bool
    {
        $words = self::getTranslations(['false', 'off', 'no', 'n']);
        return in_array($value, $words, true);
    }

    private function isNullWord(mixed $value): bool
    {
        $words = self::getTranslations(['null', '~']);
        return in_array($value, $words, true);
    }

    private function isTranslationWord(mixed $value): bool
    {
        return (
            $this->isTrueWord($value)  ||
            $this->isFalseWord($value) ||
            $this->isNullWord($value)
        );
    }

    /**
     * Coerce a string into a native type
     * Reference: http://yaml.org/type/bool.html
     * TODO: Use only words from the YAML spec.
     * 
     * @param mixed $value The value to coerce
     * @return void
     */
    private function coerceValue(mixed $value): void
    {
        if ($this->isTrueWord($value)) {
            $value = true;
        }

        if ($this->isFalseWord($value)) {
            $value = false;
        }

        if ($this->isNullWord($value)) {
            $value = null;
        }
    }

    private static function isComment($line)
    {
        if (!$line) return false;
        if ($line[0] == '#') return true;
        if (trim($line, " \r\n\t") == '---') return true;
        return false;
    }

    private static function isEmpty($line)
    {
        return (trim($line) === '');
    }


    private function isArrayElement($line)
    {
        if (!$line || !is_scalar($line)) return false;
        if (substr($line, 0, 2) != '- ') return false;
        if (strlen($line) > 3)
            if (substr($line, 0, 3) == '---') return false;

        return true;
    }

    private function isHashElement($line)
    {
        return strpos($line, ':');
    }

    private function isLiteral($line)
    {
        if ($this->isArrayElement($line)) return false;
        if ($this->isHashElement($line)) return false;
        return true;
    }

    private function isPlainArray($line)
    {
        return ($line[0] == '[' && substr($line, -1, 1) == ']');
    }
}
