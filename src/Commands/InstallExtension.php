<?php

namespace PeclExtension\Commands;

use PeclExtension\PeclExtension;
use Solital\Core\Console\Command;
use Solital\Core\Console\InputOutput;
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
     * @var PeclExtension
     */
    private PeclExtension $pecl;

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

        $this->pecl = new PeclExtension();

        if ($this->pecl->isPolyfillExists($arguments->ext_name) == true) {
            (new InputOutput('green'))->confirmDialog($arguments->ext_name . ' has a polyfill. Continue with installation?', 'Y', 'N', false)->confirm(function () use ($arguments) {
                $this->install($arguments->ext_name);
            })->refuse(function () {
                $this->error('Aborted!')->print()->exit();
            });

            return $this;
        }

        $this->install($arguments->ext_name);
        return $this;
    }

    /**
     * @param string $ext_name
     * 
     * @return mixed
     */
    private function install(string $ext_name): mixed
    {
        if (PeclExtension::getOS() == 'Windows') {
            return $this->pecl->installWindowsComponent($ext_name);
        }

        if (PeclExtension::getOS() == 'Linux') {
            return $this->pecl->installLinuxComponent($ext_name);
        }
    }
}
