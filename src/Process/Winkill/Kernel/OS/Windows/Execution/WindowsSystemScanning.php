<?php declare(strict_types=1);

namespace Winkill\Kernel\OS\Windows\Execution;

use Winkill\Kernel\Exception\SystemScanningFailure;
use Winkill\Kernel\Interface\SystemScanning;

final class WindowsSystemScanning implements SystemScanning
{
    private const COMMAND = 'tasklist';

    /**
     * @return string[]|array<int, string>
     *
     * @throws SystemScanningFailure
     */
    public function scan(): array
    {
        $processes = mb_convert_encoding(trim((string)shell_exec(self::COMMAND)), 'UTF-8', 'ISO-8859-1')
                     ?: throw new SystemScanningFailure();

        $array = preg_split('/\n|\r\n/', $processes);
        return array_splice($array, 2);
    }
}
