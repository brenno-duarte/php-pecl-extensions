<?php

namespace PeclPolyfill\Componere;

final class Patch extends Definition
{
    /* Constructors */
    public function __construct(object $instance, array $interfaces)
    {
    }

    /* Methods */
    public function apply(): void
    {
    }

    public function revert(): void
    {
    }

    public function isApplied(): bool
    {
    }

    public function derive(object $instance): Patch
    {
    }

    public function getClosure(string $name): \Closure
    {
    }

    public function getClosures(): array
    {
    }

    /* Inherited methods */
    public function addInterface(string $interface): Definition
    {
    }

    public function addMethod(string $name, Method $method): Definition
    {
    }

    public function addTrait(string $trait): Definition
    {
    }

    public function getReflector(): \ReflectionClass
    {
    }
}
