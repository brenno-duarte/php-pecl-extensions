<?php

namespace PeclPolyfill\Functions\Uopz\Components\MethodParameters;

use PeclPolyfill\Functions\Uopz\Components\MethodParameter;
use PeclPolyfill\Functions\Uopz\Components\Type;
use PeclPolyfill\Functions\Uopz\Components\Value;

class MixedParameter extends MethodParameter
{
    public function __construct(string $name, string $defaultValue = Value::NO_DEFAULT_VALUE, $isSplat = false)
    {
        parent::__construct($name, $defaultValue, Type::MIXED, $isSplat);
    }
}