<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Exception;

use PeclExtension\Process\Winkill\Kernel\Interface\Exception;

final class UnsupportedOperatingSystem extends \UnexpectedValueException implements Exception
{
    /**
     * @var string
     */
    protected $message =
        'Configuration for operating system: [' . PHP_OS_FAMILY . '] is currently unsupported.
        Please create an issue on GitHub to ask for implementing support for the required OS.';
}
