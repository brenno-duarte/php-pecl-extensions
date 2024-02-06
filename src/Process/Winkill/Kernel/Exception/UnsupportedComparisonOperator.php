<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Exception;

use PeclExtension\Process\Winkill\Kernel\Interface\Exception;
use PeclExtension\Process\Winkill\Kernel\OS\Common\Comparison;

final class UnsupportedComparisonOperator extends \InvalidArgumentException implements Exception
{
    /**
     * @param string $operator 
     * @param string $message 
     * @param int $code 
     * @param \Throwable|null $previous 
     * 
     * @return void 
     */
    public function __construct(
        string $operator,
        string $message = "",  
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = $message ?: "You try to use an unsupported compare operator: [$operator]. 
                                Use one of these instead: " . implode(', ', Comparison::values());
        parent::__construct($message, $code, $previous);
    }
}
