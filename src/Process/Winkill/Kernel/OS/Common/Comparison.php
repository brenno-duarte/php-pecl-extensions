<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\OS\Common;

enum Comparison: string
{
    case MORE_THAN = '>';
    case LESS_THAN = '<';

    case EQUAL = '=';

    case MORE_THAN_OR_EQUAL = '>=';
    case LESS_THAN_OR_EQUAL = '<=';

    case NOT_EQUAL = '!=';

    /**
     * @return string[]|array<int, string>
     */
    public static function values(): array
    {
        return array_map(
            static fn(self $operator): string => $operator->value,
            self::cases()
        );
    }

    /**
     * Process attribute to attribute value comparison
     *
     * @param mixed $compared
     * @param ...$values
     *
     * @return bool
     */
    public function compare(mixed $compared, ...$values): bool
    {
        return (bool)match ($this) {
            self::MORE_THAN => array_map(
                static fn(mixed $value): bool => $compared > $value,
                $values
            ),
            self::LESS_THAN => array_map(
                static fn(mixed $value): bool => $compared < $value,
                $values
            ),

            self::EQUAL => array_map(
                static fn(mixed $value): bool => $compared == $value,
                $values
            ),

            self::MORE_THAN_OR_EQUAL => array_map(
                static fn(mixed $value): bool => $compared >= $value,
                $values
            ),
            self::LESS_THAN_OR_EQUAL => array_map(
                static fn(mixed $value): bool => $compared <= $value,
                $values
            ),

            self::NOT_EQUAL => array_map(
                static fn(mixed $value): bool => $compared != $value,
                $values
            ),
        };
    }
}
