<?php

require_once 'vendor/autoload.php';

if (!function_exists('uopz_get_property')) {
    function uopz_get_property(string|object $class, string $property)
    {
        $reflection = new \ReflectionClass($class);
        $name = $reflection->getProperty($property);
        return $name->name;
    }
}

if (!function_exists('uopz_get_static')) {
    function uopz_get_static(string|object $class, string $property)
    {
        $reflection = new \ReflectionClass($class);
        $name = $reflection->getProperty($property);
        return $name->name;
    }
}
