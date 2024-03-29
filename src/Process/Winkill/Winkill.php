<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill;

use PeclExtension\Process\Winkill\Kernel\Exception\{UnknownOperatingSystem, UnsupportedOperatingSystem};
use PeclExtension\Process\Winkill\Kernel\Interface\Configuration;
use PeclExtension\Process\Winkill\Kernel\OS\Windows\WindowsConfiguration;
use PeclExtension\Process\Winkill\Kernel\Processes;

final class Winkill
{
    /**
     * @param Configuration|null $factory
     */
    public function __construct(
        private readonly ?Configuration $factory = null,
    ) {}

    /**
     * Composition Root
     *
     * Look at the current operating system (OS) scan all
     * active processes appropriately to it. And return
     * processes' builder to manipulate over them.
     *
     * ```
     * $terminator = new Winkill();
     * $processes = $terminator->scan();
     * ```
     * @see https://blog.ploeh.dk/2011/07/28/CompositionRoot/
     *
     * @return Processes
     *
     * @throws Kernel\Exception\SystemScanningFailure
     * @throws UnknownOperatingSystem
     * @throws UnsupportedOperatingSystem
     */
    public function scan(): Processes
    {
        $factory = match (PHP_OS_FAMILY) {
            'Windows' => new WindowsConfiguration(),
            'Unknown' => throw new UnknownOperatingSystem(),
            default => throw new UnsupportedOperatingSystem()
        };

        return new Processes($this->factory ?: $factory);
    }
}
