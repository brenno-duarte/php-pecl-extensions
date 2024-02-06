<?php

namespace PeclExtension\Commands;

use PeclExtension\PeclExtension;
use Solital\Core\Console\Command;
use Solital\Core\Console\MessageTrait;
use Solital\Core\Console\Interface\CommandInterface;

class StatusExtension extends Command implements CommandInterface
{
    use MessageTrait;

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
    protected string $description = "";

    /**
     * @param object $arguments
     * @param object $options
     * 
     * @return mixed
     */
    public function handle(object $arguments, object $options): mixed
    {
        if (!isset($arguments->ext_name)) {
            $this->error('You must enter the name of the extension')->print()->exit();
        }
        
        $pecl = new PeclExtension();
        $pecl->statusExtension($arguments->ext_name);

        return $this;
    }
}
