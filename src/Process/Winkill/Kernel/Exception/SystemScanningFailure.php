<?php declare(strict_types=1);

namespace PeclExtension\Process\Winkill\Kernel\Exception;

use PeclExtension\Process\Winkill\Kernel\Interface\Exception;

final class SystemScanningFailure extends \LogicException implements Exception
{
}
