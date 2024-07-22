<?php

namespace PeclPolyfill\Componere;

abstract class AbstractDefinition
{
    /* Methods */
    abstract public function addInterface(string $interface): Definition;
    abstract public function addMethod(string $name, Method $method): Definition;
    abstract public function addTrait(string $trait): Definition;
    abstract public function getReflector(): \ReflectionClass;
}
