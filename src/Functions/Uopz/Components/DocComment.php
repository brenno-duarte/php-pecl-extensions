<?php

namespace PeclPolyfill\Functions\Uopz\Components;

class DocComment extends Component
{
    protected $docComment;
    protected $paramsComment = [];
    protected $returnComment = '';

	public function __construct(string $docComment = '')
	{
		$this->docComment = $docComment;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
	    if (
	        strlen(trim($this->docComment)) === 0 &&
            count($this->paramsComment) === 0 &&
            strlen(trim($this->returnComment)) === 0
        ) {
	        return '';
        }

	    $identation = new Indentation();

		return
			$identation . '/**' . "\n" .
            ($this->docComment? $identation . " * " . str_replace("\n", "\n".$identation." * ", $this->docComment) . "\n" : '').
            rtrim(array_reduce(
                $this->paramsComment,
                function($carry, $item) use ($identation) {
                    $carry .= $identation . ' * ' .  $item . "\n";
                    return $carry;
                },
                ''
            )) .
            ( $this->returnComment? "\n". $identation . ' * ' . $this->returnComment : '') .
			"\n" . $identation . ' */' . "\n";
		;
	}

    public function getComment()
    {
        return $this->docComment;
    }

	public function addComment(string $comment)
	{
		$this->docComment .= "\n" . $comment;
	}

	public function addMethodParameterComment(MethodParameter $parameter)
    {
        $this->paramsComment[] = '@param ' .
            ($parameter->getType() ?? 'mixed') .
            ($parameter->isSplat()? '[] ...' : '') .
            '$' .
            $parameter->getName()
        ;
    }

    public function addMethodReturnTypeComment(string $returnType, bool $mightBeNull)
    {
        $this->returnComment = '@return ' . $returnType . ($mightBeNull? '|null' : '');
    }
}