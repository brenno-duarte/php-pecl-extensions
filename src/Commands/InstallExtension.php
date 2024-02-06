<?php

namespace PeclExtension\Commands;

use PeclExtension\PeclExtension;
use Solital\Core\Console\Command;
use Solital\Core\Console\MessageTrait;
use Solital\Core\Console\Interface\CommandInterface;

class InstallExtension extends Command implements CommandInterface
{
    use MessageTrait;

    /**
     * @var string
     */
    protected string $command = "install";

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

        if ($pecl->isPolyfillExists($arguments->ext_name) == true) {
            $this->warning($arguments->ext_name . ': Polyfill found! Use polyfill class or function')->print();
            return false;
        }

        if (PeclExtension::getOS() == 'Windows') {
            return $pecl->installWindowsComponent($arguments->ext_name);
        }

        return $this;
    }
}
