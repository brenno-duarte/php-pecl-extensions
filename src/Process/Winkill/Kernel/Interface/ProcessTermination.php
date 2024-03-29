<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Interface;

use PeclExtension\Process\Winkill\Kernel\Exception\ProcessTerminationFailure;

/**
 * Strategy Pattern
 *
 * @see https://refactoring.guru/design-patterns/strategy
 */
interface ProcessTermination
{
    /**
     * @param Process $process
     *
     * @return void
     *
     * @throws ProcessTerminationFailure
     */
    public function terminate(Process $process): void;
}
