<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Indentation extends Component
{
	const TABS = "\t";
	const SPACES = ' ';

	protected static $indentation;

	public static function setIndentation(string $type, int $repetition)
	{
		self::$indentation = str_pad('', $repetition, $type);
	}

    public function __toString(): string
    {
        return self::$indentation ?? '    ';
    }
}