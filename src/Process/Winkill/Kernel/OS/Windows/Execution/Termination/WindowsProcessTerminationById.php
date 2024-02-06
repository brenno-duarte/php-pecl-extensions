<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\OS\Windows\Execution\Termination;

use PeclExtension\Process\Winkill\Kernel\Exception\ProcessTerminationFailure;
use PeclExtension\Process\Winkill\Kernel\Interface\{Process, ProcessTermination};

final class WindowsProcessTerminationById implements ProcessTermination
{
    private const COMMAND = 'taskkill /F /PID <attribute>';

    /**
     * @param Process $process
     *
     * @return void
     *
     * @throws ProcessTerminationFailure
     */
    public function terminate(Process $process): void
    {
        exec((string)str_replace(
            search: '<attribute>',
            replace: (string)$process->process_id,
            subject: self::COMMAND
        )) ?: throw new ProcessTerminationFailure($process);
    }
}
