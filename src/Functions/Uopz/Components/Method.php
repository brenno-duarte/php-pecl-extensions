<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class Method extends Component
{
    protected $name;
    protected $visibility;
	protected $isStatic = false;
	protected $isFinal = false;
	protected $isAbstract = false;
	protected $returnType;
	protected $parameter = [];
	protected $mightReturnNull = false;
	protected $docComment;

	/**
	 * Method constructor.
	 * @param string $name
	 * @param string $visibility
	 */
    public function __construct(string $name, string $visibility = Visibility::NONE)
    {
        $this->name = $name;
        $this->visibility = new Visibility($visibility);

    }

    /**
     * @return string
     */
    public function __toString(): string
	{
		$docComment = clone ($this->docComment ?? new DocComment());
        $indentation = new Indentation();

		$parametersString = '';
		foreach ($this->parameter as $parameter) {
            $parametersString .= $parameter.', ';
            $docComment->addMethodParameterComment($parameter);
		}

		if ($this->returnType) {
            $docComment->addMethodReturnTypeComment($this->returnType, $this->mightReturnNull);

        }

		return
			$docComment .
            $indentation .
            ($this->isAbstract? 'abstract ' :  '' ) .
            ($this->isFinal? 'final ' : '' ) .
            $this->visibility .
            ($this->isStatic? 'static ' : '') .
			'function ' .
			$this->name . '(' .
			rtrim($parametersString, ', '). ')' .
            ($this->returnType ? ': ' . ($this->mightReturnNull ? '?':'') . $this->returnType : '') .
            "\n"  .
            $indentation .'{' .
			"\n" .
			"\n" .
            $indentation . '}' .
			"\n"
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

	/**
	 * @return bool
	 */
	public function isFinal(): bool
	{
		return $this->isFinal;
	}

	/**
	 * @param bool $isFinal
	 */
	public function setIsFinal(bool $isFinal)
	{
		$this->isFinal = $isFinal;
        $this->isAbstract = $isFinal ? false: $this->isAbstract;
	}

	/**
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		return $this->isAbstract;
	}

	/**
	 * @param bool $isAbstract
	 */
	public function setIsAbstract(bool $isAbstract)
	{
		$this->isAbstract = $isAbstract;
		$this->isFinal = $isAbstract ? false : $this->isFinal;
	}

	/**
	 * @return string|null
	 */
	public function getReturnType()
	{
		return $this->returnType;
	}

    /**
     * @param string $returnType
     * @param bool $orNull
     */
	public function setReturnType(string $returnType, $orNull = false)
	{
		$this->returnType = $returnType;
		$this->mightReturnNull = $orNull;
	}

	/**
	 * @return array
	 */
	public function getParameters(): array
	{
		return $this->parameter;
	}

    /**
     * @param MethodParameter $parameter
     */
	public function addParameter(MethodParameter $parameter)
	{
		$this->parameter[] = $parameter;
	}

    /**
     * @return mixed
     */
    public function getDocComment()
    {
        return $this->docComment ? $this->docComment->getComment() : null;
    }

    /**
     * @param string $docComment
     */
    public function setDocComment(string $docComment)
    {
        $this->docComment = new DocComment($docComment);
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility->getName();
    }
}