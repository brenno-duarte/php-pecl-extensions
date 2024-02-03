<?php

namespace PeclExtension;

use PeclExtension\Commands\InstallExtension;
use PeclExtension\Commands\ListExtensions;
use PeclExtension\Commands\Service;
use PeclExtension\Commands\StatusExtension;
use Solital\Core\Console\Interface\ExtendCommandsInterface;

class PeclCommands implements ExtendCommandsInterface
{
    protected array $command_class = [
        InstallExtension::class,
        StatusExtension::class,
        ListExtensions::class,
        Service::class
    ];

    public function getCommandClass(): array
    {
        return $this->command_class;
    }

    public function getTypeCommands(): string
    {
        return "PeclExtension Commands";
    }
}
