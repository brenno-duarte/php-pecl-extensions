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
    protected string $description = "Execute or list all Windows services";

    /**
     * @param object $arguments
     * @param object $options
     * 
     * @return mixed
     */
    #[\Override]
    public function handle(object $arguments, object $options): mixed
    {
        $pecl = new PeclExtension();

        if (isset($options->list)) {
            $pecl->listWindowsServices();
            return true;
        }

        if (!isset($arguments->bin_name)) {
            $this->error('You must enter the name of the service')->print()->exit();
        }

        if (PeclExtension::getOS() == 'Windows') {
            $pecl->serviceWindows($arguments->bin_name, $options);
        }

        if (PeclExtension::getOS() == 'Linux') {
            $this->error('This command is only for Windows system')->print()->exit();
        }

        return $this;
    }
}
