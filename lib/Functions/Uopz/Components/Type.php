<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Type extends Component
{
    protected $value;
    protected $type;

    const ARRAY = 'array';
    const MIXED = 'mixed';
    const BOOL = 'bool';
    const CALLABLE = 'callable';
    const FLOAT = 'float';
    const INT = 'int';
    const STDCLASS = 'stdClass';
    const STRING = 'string';
    const NONE = null;

    public function __construct(?string $type = self::NONE)
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type ? $this->type . ' ' : '';
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


}