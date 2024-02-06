<?php

namespace PeclExtension\Commands;

use PeclExtension\PeclExtension;
use Solital\Core\Console\Command;
use Solital\Core\Console\MessageTrait;
use Solital\Core\Console\Interface\CommandInterface;

class Service extends Command implements CommandInterface
{
    use MessageTrait;

    /**
     * @var string
     */
    protected string $command = "service";

    /**
     * @var array
     */
    protected array $arguments = ["bin_name"];

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
        if (!isset($arguments->bin_name)) {
            $this->error('You must enter the name of the service')->print()->exit();
        }

        $pecl = new PeclExtension();

        if (!isset($arguments->bin_name)) {
            $pecl->listServices();
            return true;
        }

        if (PeclExtension::getOS() == 'Windows') {
            $pecl->serviceWindows($arguments->bin_name, $options);
        }

        return $this;
    }
}
