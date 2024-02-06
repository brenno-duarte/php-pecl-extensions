<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Interface;

use PeclExtension\Process\Winkill\Kernel\Exception\ProcessParsingFailure;

/**
 * Strategy Pattern + Factory Method Pattern
 *
 * @see https://refactoring.guru/design-patterns/strategy
 * @see https://refactoring.guru/design-patterns/factory-method
 */
interface ProcessParsing
{
    /**
     * @param string $process
     *
     * @return Process
     *
     * @throws ProcessParsingFailure
     */
    public function parse(string $process): Process;
}
