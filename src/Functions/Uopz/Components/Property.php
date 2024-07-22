<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Property extends Component
{
    protected $name;
    protected $visibility;
    protected $defaultValue;
    protected $isStatic = false;

    public function __construct(string $name, string $visibility = null, string $defalutValue = null, bool $isStatic = false)
    {
        $this->name = $name;
        $this->visibility = new Visibility($visibility);
        $this->defaultValue = $defalutValue? new Value($defalutValue) : null;
        $this->isStatic = $isStatic;
    }

    public function __toString(): string
    {
        $indentation = new Indentation();

        return $indentation .
            $this->visibility .
            ($this->isStatic ? 'static ' : '') .
            '$' .
            $this->name  .
            ($this->defaultValue ? ' = ' . $this->defaultValue : '').
            ';'

        ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Visibility
     */
    public function getVisibility(): string
    {
        return $this->visibility->getName();
    }

    /**
     * @param string $visibility
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = new Visibility($visibility);
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue(string $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     */
    public function setIsStatic(bool $isStatic)
    {
        $this->isStatic = $isStatic;
    }
}