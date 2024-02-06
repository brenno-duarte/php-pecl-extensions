<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Exception;

use PeclExtension\Process\Winkill\Kernel\Interface\Exception;

final class UnknownOperatingSystem extends \UnexpectedValueException implements Exception
{
    /**
     * @var string
     */
    protected $message = 'Cannot make configuration for an unknown operating system.';
}
