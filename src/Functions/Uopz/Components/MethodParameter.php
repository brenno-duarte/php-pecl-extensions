<?php

namespace PeclPolyfill\Functions\Uopz\Components;

abstract class MethodParameter extends Component
{
    protected $name;
	protected $type;
    protected $defaultValue;
    protected $isSPlat = false;

	public function __construct(string $name, string $defaultValue = null, string $type = null, $isSplat = false)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue? new Value($defaultValue, $type) : null;
        $this->type = new Type($type);
        $this->isSPlat = $isSplat;
    }

    public function __toString(): string
    {
        $type = $this->type->getType() === Type::MIXED ? new Type(Type::NONE) : $this->type;

        $parameterString = $type .
            ($this->isSPlat? '...' : '') .
            '$' . $this->name;

        if ($this->defaultValue) {
            $parameterString .= ' = ' .$this->defaultValue;
        }

        return $parameterString;
    }

    public function getName()
    {
        return $this->name;
    }

	public function getType()
	{
		return $this->type;
	}

    /**
     * @return bool
     */
    public function isSPlat(): bool
    {
        return $this->isSPlat;
    }

    /**
     * @param bool $isSPlat
     */
    public function setIsSPlat(bool $isSPlat)
    {
        $this->isSPlat = $isSPlat;
    }
}