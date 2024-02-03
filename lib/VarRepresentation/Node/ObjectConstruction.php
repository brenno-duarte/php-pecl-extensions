<?php

declare(strict_types=1);

namespace PeclPolyfill\VarRepresentation\Node;

use PeclPolyfill\VarRepresentation\Node;
use PeclPolyfill\VarRepresentation\Node\ArrayLiteral;

/**
 * Represents an object construction.
 */
class ObjectConstruction extends Node
{
    /** @var string prefix (e.g. ArrayObject::__set_state() */
    protected string $prefix;

    /** @var ArrayLiteral inner array */
    protected ArrayLiteral $array;

    /** @var string suffix (e.g. ')') */
    protected string $suffix;

    public function __construct(string $prefix, ArrayLiteral $array, string $suffix)
    {
        if ($prefix === 'stdClass::__set_state(') {
            $this->prefix = '(object) ';
            $this->suffix = '';
        } else {
            $this->prefix = \PHP_VERSION_ID >= 80200 ? $prefix : '\\' . $prefix;
            $this->suffix = $suffix;
        }
        $this->array = $array;
    }

    public function __toString(): string
    {
        if ($this->prefix === 'stdClass::__set_state(') {
            return '(object) ' . $this->array;
        }
        return $this->prefix . $this->array->__toString() . $this->suffix;
    }

    public function toIndentedString(int $depth): string
    {
        return $this->prefix . $this->array->toIndentedString($depth) . $this->suffix;
    }
}
