<?php

namespace PeclPolyfill\Componere;

use PeclPolyfill\Functions\Uopz\ClassEditor;
//use PeclPolyfill\Functions\Uopz\Components\Method;

final class Definition extends AbstractDefinition
{
    private ClassEditor $class_editor;
    private string $method;
    private $class;
    private string $content;
    private Container $container;

    /* Constructors */
    public function __construct(string $name, string $parent = '', array $interfaces = [])
    {
        throw new \Exception("DON'T use this component yet");
        
        /* if (!class_exists($name)) {
            throw new \Exception("Class '" . $name . "' not exists");
        } */

        if (!file($name . '.php') && !class_exists($name)) {
            throw new \Exception("Class '" . $name . "' not exists");
        }

        $this->class = $name . '.php';
        $this->content = file_get_contents($name . '.php');

        //$this->class = $name;

        //$this->container = new Container($name);
        //$this->class_editor = new ClassEditor($name . '.php');
    }

    /* Methods */
    public function addConstant(string $name, Value $value): Definition
    {
    }

    public function addProperty(string $name, Value $value): Definition
    {
    }

    public function register(): void
    {
        try {
            file_put_contents($this->class, $this->content);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }

        //$this->class_editor->save();
    }

    public function isRegistered(): bool
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
        $visibility = 'public';
        $is_static = '';

        $reflection = new \ReflectionClass($method);

        if ($reflection->getProperty('visibility')->getValue($method) != '') {
            $visibility = $reflection->getProperty('visibility')->getValue($method);
        }

        if ($reflection->getProperty('is_static')->getValue($method) != '') {
            $is_static = $reflection->getProperty('is_static')->getValue($method);
        }

        $closure = $reflection->getProperty('closure_method')->getValue($method);
        $closure = $this->closureDump($closure, $name);
        $closure = str_replace('});', '}', $closure);
        $closure = str_pad($closure, 10, pad_type: STR_PAD_LEFT);

        //var_dump($closure);exit;

        $method = $visibility . ' ' . $is_static . $closure;

        $lastClosingBrackets = strrpos($this->content, '}');

        $this->content = substr($this->content, 0, $lastClosingBrackets) .
            "\n" .
            $method .
            "\n" .
            substr($this->content, $lastClosingBrackets);

        var_dump($this->content);exit;

        return $this;
    }

    public function addTrait(string $trait): Definition
    {
    }

    public function getReflector(): \ReflectionClass
    {
        return new \ReflectionClass($this->class);
    }

    private function closureDump(\Closure $c, string $method_name)
    {
        $str = 'function ' . $method_name . '(';
        $r = new \ReflectionFunction($c);
        $params = [];

        foreach ($r->getParameters() as $p) {
            $s = '';

            if ($p->getType() && $p->getType()->getName() === 'array') {
                $s .= 'array ';
            } else if ($this->getClass($p)) {
                $s .= $this->getClass($p)->name . ' ';
            }

            if ($p->isPassedByReference()) {
                $s .= '&';
            }

            $s .= '$' . $p->name;

            if ($p->isOptional()) {
                $s .= ' = ' . var_export($p->getDefaultValue(), TRUE);
            }

            $params[] = $s;
        }

        $str .= implode(', ', $params);
        $str .= '){' . PHP_EOL;
        $lines = file($r->getFileName());

        for ($l = $r->getStartLine(); $l < $r->getEndLine(); $l++) {
            $str .= $lines[$l];
        }

        return $str;
    }

    /**
     * Get parameter class
     * @param \ReflectionParameter $parameter
     * @return \ReflectionClass|null
     */
    private function getClass(\ReflectionParameter $parameter): ?\ReflectionClass
    {
        $type = $parameter->getType();

        if (!$type || $type->isBuiltin()) {
            return null;
        }

        // This line triggers autoloader!
        if (!class_exists($type->getName())) {
            return null;
        }

        return new \ReflectionClass($type->getName());
    }
}
