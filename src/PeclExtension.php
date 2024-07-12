<?php

namespace PeclExtension;

use Matomo\Ini\IniReader;
use PeclExtension\Process;
use Solital\Core\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PeclExtension
{
    const OS_UNKNOWN = 'Unknown';
    const OS_WIN = 'Windows';
    const OS_LINUX = 'Linux';
    const OS_OSX = 'MacOS';

    /**
     * @var Finder
     */
    private Finder $finder;

    /**
     * @var array
     */
    private array $php_info = [];

    /**
     * @var string
     */
    private string $dll_extensions_dir = '';

    /**
     * @var array
     */
    private array $extensions_with_polyfill = [
        'base58', 'ds', 'HRTime', 'inotify', 'JSONPath', 'OAuth',
        'scrypt', 'simdjson', 'sodium', 'ssdeep', 'statistic (partial)',
        'translit', 'uopz (partial)', 'var_representation', 'xxtea',
        'xdiff (partial)', 'yaconf', 'yaml'
    ];

    public function __construct()
    {
        $this->finder = new Finder();

        $this->PhpInfo();
        $this->dll_extensions_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "ext" . DIRECTORY_SEPARATOR;
    }

    /**
     * Check if PECL polyfill exists
     *
     * @param string $name
     * 
     * @return bool
     */
    public function isPolyfillExists(string $name): bool
    {
        if (array_search($name, $this->extensions_with_polyfill)) {
            return true;
        }

        return false;
    }

    /**
     * Install DLL files for Windows
     *
     * @param string $name
     * 
     * @return bool|null
     */
    public function installWindowsComponent(string $name): ?bool
    {
        if (extension_loaded($name)) {
            ConsoleOutput::success($name . ": extension already installed")->print()->exit();
        }

        $url = "https://raw.githubusercontent.com/brenno-duarte/pecl/main/extensions/" .
            $name . "/" . $name . "-" . $this->php_info['php_version'] . "-x64-" .
            $this->php_info['thread_safe'] . "/php_" . $name . ".dll";

        $extension_dir_file = $this->php_info["extensions_dir"] . "php_" . $name . ".dll";
        $extension_dir_temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "php_" . $name . ".dll";
        $data = @fopen($url, "r");

        if ($data == false) ConsoleOutput::error("Extension `" . $name . "` not found on repository")->print()->exit();
        $file = file_put_contents($extension_dir_temp, $data);

        if ($file == false) ConsoleOutput::error("Failed to create DLL file")->print()->exit();
        $is_moved = rename($extension_dir_temp, $extension_dir_file);

        if ($is_moved == true && is_file($extension_dir_file)) {
            ConsoleOutput::success($name . ": extension installed")->print();

            if (file_exists($extension_dir_temp)) unlink($extension_dir_temp);
            clearstatcache();
            $this->isExtensionEnable($name);
            return true;
        }

        ConsoleOutput::error($name . ': extension not found for your PHP version or (Non)Thread Safe')->print();
        return false;
    }

    /**
     * Install extensions for Linux
     *
     * @param string $name
     * 
     * @return mixed
     */
    public function installLinuxComponent(string $name): mixed
    {
        return Process::executeCommand('sudo apt install php' . $this->php_info['php_version'] . '-' . $name);
    }

    /**
     * List if extension is enabled and/or installed
     *
     * @param string $name
     * 
     * @return PeclExtension
     */
    public function statusExtension(string $name): PeclExtension
    {
        if (PeclExtension::getOS() == 'Windows') $ext = 'dll';
        if (PeclExtension::getOS() == 'Linux') $ext = 'so';

        if (is_file($this->php_info['extensions_dir'] . 'php_' . $name . '.' . $ext)) {
            ConsoleOutput::success($name . ': extension installed')->print();
            $this->isExtensionEnable($name);
            return $this;
        }

        ConsoleOutput::warning($name . ': extension not installed')->print()->break();
        return $this;
    }

    /**
     * List polyfill and DLL available
     *
     * @return void
     */
    public function listExtensions(): void
    {
        ConsoleOutput::line('PECL Extensions with polyfill available')->print()->break();

        foreach ($this->extensions_with_polyfill as $ext) {
            ConsoleOutput::success('    ' . $ext)->print()->break();
        }

        echo PHP_EOL;

        if (PeclExtension::getOS() == 'Windows') {
            ConsoleOutput::line('PECL Extensions with DLL files')->print()->break();
            ConsoleOutput::info('Use \'vendor\bin\pecl install <extension_name>\'')->print()->break();

            $this->finder->directories()->in($this->dll_extensions_dir)->depth('==0');

            foreach ($this->finder as $finder) {
                ConsoleOutput::success('    ' . $finder->getBasename())->print()->break();
            }
        }
    }

    /**
     * Get OS used
     * 
     * @return string
     */
    public static function getOS(): string
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                return self::OS_OSX;
            case stristr(PHP_OS, 'WIN'):
                return self::OS_WIN;
            case stristr(PHP_OS, 'LINUX'):
                return self::OS_LINUX;
            default:
                return self::OS_UNKNOWN;
        }
    }

    private function isExtensionEnable(string $name): void
    {
        if (extension_loaded($name) == true) {
            ConsoleOutput::success(' and enabled on "php.ini"')->print()->break();
        } else {
            ConsoleOutput::success(', but not enabled. Add this line on your "php.ini"')->print()->break(true);
            $this->configIniComponent($name);
        }
    }

    /**
     * @param string $name
     * 
     * @return string
     */
    private function configIniComponent(string $name): void
    {
        $url = "https://github.com/brenno-duarte/pecl/tree/main/extensions-required-files/";

        switch ($name) {
            case "apcu":
                ConsoleOutput::info("[apcu]")->print()->break();
                ConsoleOutput::info("extension=apcu")->print()->break();
                ConsoleOutput::info("apc.enabled=1")->print()->break();
                ConsoleOutput::info("apc.shm_size=32M")->print()->break();
                ConsoleOutput::info("apc.ttl=7200")->print()->break();
                ConsoleOutput::info("apc.enable_cli=1")->print()->break();
                ConsoleOutput::info("apc.serializer=php")->print();
                break;

            case "pcov":
                ConsoleOutput::info("[pcov]")->print()->break();
                ConsoleOutput::info("extension=pcov")->print()->break();
                ConsoleOutput::info("pcov.enabled=1")->print()->break();
                ConsoleOutput::info("pcov.directory=/path/to/your/source/directory")->print();
                break;

            case "imagick":
                ConsoleOutput::info("[imagick]")->print()->break();
                ConsoleOutput::info("extension=imagick")->print()->break();

                if (self::getOS() == "Windows") {
                    echo PHP_EOL;
                    ConsoleOutput::success("See this link " . $url . "imagick/imagick-" . $this->php_info["php_version"] . "-x64-" . $this->php_info["thread_safe"] . "/")->print()->break();
                    ConsoleOutput::success("And copy all DLL files into the PHP installation directory (in the same directory as `php.exe`)")->print();
                }
                break;

            case "yac":
                ConsoleOutput::info("[yac]")->print()->break();
                ConsoleOutput::info("extension=yac")->print()->break();
                ConsoleOutput::info("yac.enable_cli=1")->print();
                break;

            case "xhprof":
                ConsoleOutput::info("[xhprof]")->print()->break();
                ConsoleOutput::info("extension=xhprof")->print()->break();
                ConsoleOutput::info("xhprof.output_dir=/tmp/xhprof")->print()->break();
                ConsoleOutput::info("xhprof.sampling_interval=100000")->print()->break();
                ConsoleOutput::info("xhprof.collect_additional_info=0")->print()->break(true);
                ConsoleOutput::success("See aditional files in " . $url . "xhprof/")->print();
                break;

            default:
                ConsoleOutput::info("extension=" . $name)->print();
                break;
        }
    }

    /**
     * List info about PHP
     *
     * @return array
     */
    private function PhpInfo(): array
    {
        if (ZEND_THREAD_SAFE == true) {
            $this->php_info['thread_safe'] = 'ts';
        } else {
            $this->php_info['thread_safe'] = 'nts';
        }

        $compiler = shell_exec("php -i | findstr Compiler");
        $compiler = explode("=>", trim($compiler));

        $this->php_info['compiler'] = trim($compiler[1]);
        $this->php_info['php_version'] = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        $this->php_info['extensions_dir'] = $this->PhpExtensionDir();

        return $this->php_info;
    }

    /**
     * @return string
     */
    private function PhpExtensionDir(): string
    {
        $ini_file = php_ini_loaded_file();
        $reader = new IniReader();
        $array = $reader->readFile($ini_file);

        if (isset($array['PHP']['extension_dir']) && $array['PHP']['extension_dir'] != "ext") {
            $ext_dir = $array['PHP']['extension_dir'];
        } else {
            $ext_dir = dirname($ini_file) . DIRECTORY_SEPARATOR . 'ext' . DIRECTORY_SEPARATOR;
        }

        return $ext_dir;
    }
}
