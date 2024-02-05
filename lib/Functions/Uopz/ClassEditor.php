<?php

namespace PeclPolyfill\Functions\Uopz;

use PeclPolyfill\Functions\Uopz\Components\Constant;
use PeclPolyfill\Functions\Uopz\Components\Indentation;
use PeclPolyfill\Functions\Uopz\Components\Method;
use PeclPolyfill\Functions\Uopz\Components\Property;
use PeclPolyfill\Functions\Uopz\Exceptions\ClassEditorException;

/**
 * Edits a php file representing a class and dynamically renames, adds, removes... methods and attributes
 */
class ClassEditor
{
	private $phpFilePath;
	private $content;

	/**
	 * ClassEditor constructor.
	 * @param string $phpFilePath the path to the php class
	 * @throws ClassEditorException when the file does not exists or file extension is not php
	 */
	public function __construct(string $phpFilePath)
	{
		if (!file_exists($phpFilePath) || pathinfo($phpFilePath, PATHINFO_EXTENSION) !== 'php') {
			throw new ClassEditorException($phpFilePath . ' is not a valid php file');
		}

		$this->phpFilePath = $phpFilePath;
		$this->content = file_get_contents($phpFilePath);
	}

	/**
	 * Sets the indentation to use when adding methods or attributes.
	 * Default indentation is 4 spaces
	 *
	 * @param string $type the type of indentation to use : either Indentation::TABS or Indentation::SPACES
	 * @param int $repetition the number of tabs or spaces that defines 1 indentation
	 */
	public function useIndentation(string $type, int $repetition)
	{
		Indentation::setIndentation($type, $repetition);
	}

    /**
     * Adds all the methods sent as parameters dynamically inside the php class
     * @param Method $method
     * @return string
     */
	public function addMethod(Method $method): string
    {
        $lastClosingBrackets = strrpos($this->content, '}');
        $this->content = substr($this->content, 0, $lastClosingBrackets) .
            "\n" .
            $method .
            "\n" .
            substr($this->content, $lastClosingBrackets);

        return $this->content;
    }

    /**
     * Adds all the properties sent as dynamically inside the php class
     * @param Property $property
     * @return string
     */
    public function addProperty(Property $property): string
    {
        $lastOpenedBrackets = strpos($this->content, '{');

        $this->content = substr($this->content, 0, $lastOpenedBrackets + 1) ."\n" .
            $property .
            substr($this->content, $lastOpenedBrackets + 1);

        return $this->content;
    }

    /**
     * Adds all the properties sent as dynamically inside the php class
     * @param Constant $constant
     * @return string
     */
    public function addConst(Constant $constant): string
    {
        $lastOpenedBrackets = strpos($this->content, '{');

        $this->content = substr($this->content, 0, $lastOpenedBrackets + 1) ."\n" .
            $constant .
            substr($this->content, $lastOpenedBrackets + 1);

        return $this->content;
    }

    /**
     * saves all the modifications to the nex file
     */
    public function save()
    {
        file_put_contents($this->phpFilePath, $this->content);
    }
}
