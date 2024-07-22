<?php

namespace PeclPolyfill\Componere;

use PeclPolyfill\Functions\Uopz\Components\Method as ComponentsMethod;
use PeclPolyfill\Functions\Uopz\Components\Visibility;

final class Method
{
    //private ComponentsMethod $method;
    private string $visibility = '';
    private string $is_static = '';
    private \Closure $closure_method;

    const IS_PRIVATE = 'private';
    const IS_PROTECTED = 'protected';
    const IS_STATIC = 'static ';

    /* Constructor */
    public function __construct(\Closure $closure)
    {
        $this->closure_method = $closure;
        //$this->method = new ComponentsMethod('test', );
    }

    /* Methods */
    public function setPrivate(): Method
    {
        $this->visibility = self::IS_PRIVATE;
        return $this;
    }

    public function setProtected(): Method
    {
        $this->visibility = self::IS_PROTECTED;
        return $this;
    }

    public function setStatic(): Method
    {
        $this->is_static = self::IS_STATIC;
        return $this;
    }

    public function return()
    {
        $reflection = $this->getReflector();
        $res = $reflection->getClosure($this->closure_method);
        var_dump($res);
    }

    public function getReflector(): \ReflectionMethod
    {
        return new \ReflectionMethod($this->closure_method);
    }
}
