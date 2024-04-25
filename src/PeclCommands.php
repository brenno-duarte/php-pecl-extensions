<?php

namespace PeclExtension;

use PeclExtension\Commands\{InstallExtension, ListExtensions, StatusExtension};
use Solital\Core\Console\Interface\ExtendCommandsInterface;

class PeclCommands implements ExtendCommandsInterface
{
    protected array $command_class = [
        InstallExtension::class,
        StatusExtension::class,
        ListExtensions::class
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
