<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\OS\Windows\Execution\Termination;

use PeclExtension\Process\Winkill\Kernel\Exception\ProcessTerminationFailure;
use PeclExtension\Process\Winkill\Kernel\Interface\{Process, ProcessTermination};

final class WindowsProcessTerminationByName implements ProcessTermination
{
    private const COMMAND = 'taskkill /IM "<attribute>" /F';

    /**
     * @param Process $process
     *
     * @return void
     *
     * @throws ProcessKillingFailure
     */
    public function terminate(Process $process): void
    {
        exec((string)str_replace(
            search: '<attribute>',
            replace: $process->process_name,
            subject: self::COMMAND
        )) ?: throw new ProcessTerminationFailure($process);
    }
}
