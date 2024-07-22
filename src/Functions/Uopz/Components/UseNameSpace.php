<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class UseNameSpace extends Component
{
    protected static $namespaces = [];

    public static function addNameSpace(string $namespace)
    {
        self::$namespaces[] = $namespace;
    }

    public function __toString(): string
    {
        return array_reduce(
            self::$namespaces,
            function($carry, $namespace) {
                $carry .= 'use ' . $namespace . ';' . "\n";
                return $carry;
            },
            ''
        );
    }

    public static function getNameSpaces(): array
    {
        return self::$namespaces;
    }
}