<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Value extends Component
{
    protected $value;
    protected $type;

    const NULL = 'null';
    const TRUE = 'true';
    const FALSE = 'false';
    const EMPTY_ARRAY = '[]';
    const NO_DEFAULT_VALUE = null;

    public function __construct(string $value, string $type = null)
    {
        $this->value = $value;
        $this->type = new Type($type);

        //try to guess the type, might be wrong, but it's a guess
        if (!isset($type) || $type === Type::MIXED) {
            if (in_array(strtolower($this->value), [self::TRUE, self::FALSE])) {
                $this->type = new Type(Type::BOOL);
            } else if (is_numeric($this->value)) {
                $this->type = new Type(Type::FLOAT); //int or float does not matter
            } else if (strpos($this->value, 'array(') === 0 || strpos($this->value, '[') === 0) {
                $this->type = new Type(Type::ARRAY);
            } else {
                $this->type =  new Type(Type::STRING);
            }
        }

    }

    public function __toString(): string
    {
        if (strtolower($this->value) === 'null' || in_array(strtolower($this->type->getType()), [Type::INT, Type::FLOAT, Type::BOOL, Type::ARRAY])) {
            return  $this->value;
        } else if (strpos($this->value, '\'') === false) {
            return '\'' .  $this->value .'\'' ;
        } else {
            return '"' .  $this->value . '"';
        }
    }
}

