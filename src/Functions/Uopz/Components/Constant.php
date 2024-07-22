<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Constant extends Component
{
    protected $name;
    protected $value;
    protected $visibility;

    public function __construct(string $name, string $value, string $visibility = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->visibility = new Visibility($visibility);
    }

    public function stroke(): string
    {
        $indentation = new Indentation();

        return $indentation->stroke() .
            $this->visibility->stroke() .
            $this->name  .
            ' = ' . $this->value .
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
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->visibility = $value;
    }

    public function __toString(): string
    {
        return '';
    }
}