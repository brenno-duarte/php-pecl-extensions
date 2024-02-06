<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\OS\Windows;

use PeclExtension\Process\Winkill\Kernel\Interface\{
    Configuration,
    ProcessTermination,
    ProcessParsing,
    SystemScanning
};
use PeclExtension\Process\Winkill\Kernel\OS\Common\Configuration as CachedConfiguration;
use PeclExtension\Process\Winkill\Kernel\OS\Windows\Execution\{
    Termination\WindowsProcessTerminationById,
    WindowsProcessParsing,
    WindowsSystemScanning
};

final class WindowsConfiguration extends CachedConfiguration implements Configuration
{
    /**
     * @return SystemScanning
    */
    public function createScanningStrategy(): SystemScanning
    {
        $this->cache[SystemScanning::class] ??= new WindowsSystemScanning();
        
        return parent::getCachedScanningStrategy();
    }

    /**
     * @return ProcessParsing
    */
    public function createParsingStrategy(): ProcessParsing
    {
        $this->cache[ProcessParsing::class] ??= new WindowsProcessParsing();
        
        return parent::getCachedParsingStrategy();
    }

    /**
     * @return ProcessTermination
    */
    public function createTerminationStrategy(): ProcessTermination
    {
        $this->cache[ProcessTermination::class] ??= new WindowsProcessTerminationById();

        return parent::getCachedTerminationStrategy();
    }
}
