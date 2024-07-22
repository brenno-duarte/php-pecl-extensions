<?php

namespace PeclPolyfill\Componere;

class Container
{
    protected $target;
    protected $className;
    protected $methods = [];

    public function __construct($target)
    {
        $this->target = $target;
    }

    public function attach($name, $method)
    {
        if (!$this->className) {
            $this->className = get_class($this->target);
        }
        
        $binded = \Closure::bind($method, $this->target, $this->className);
        $this->methods[$name] = $binded;
    }

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->methods)) {
            return call_user_func_array($this->methods[$name], $arguments);
        }

        if (method_exists($this->target, $name)) {
            return call_user_func_array(
                array($this->target, $name),
                $arguments
            );
        }
    }
}
