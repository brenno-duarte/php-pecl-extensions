<?php

namespace PeclExtension\Commands;

use PeclExtension\PeclExtension;
use Solital\Core\Console\Command;
use Solital\Core\Console\Interface\CommandInterface;
use Solital\Core\Console\Output\ConsoleOutput;

class StatusExtension extends Command implements CommandInterface
{
    /**
     * @var string
     */
    protected string $command = "status";

    /**
     * @var array
     */
    protected array $arguments = ["ext_name"];

    /**
     * @var string
     */
    protected string $description = "List if an extension is enabled or installed";

    /**
     * @param object $arguments
     * @param object $options
     * 
     * @return mixed
     */
    #[\Override]
    public function handle(object $arguments, object $options): mixed
    {
        if (!isset($arguments->ext_name)) {
            ConsoleOutput::error('You must enter the name of the extension')->print()->exit();
        }
        
        $pecl = new PeclExtension();
        $pecl->statusExtension($arguments->ext_name);

        return $this;
    }
}
