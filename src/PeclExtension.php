<?php

namespace PeclExtension;

use Matomo\Ini\IniReader;
use PeclExtension\Process\Process;
use Solital\Core\Console\MessageTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PeclExtension
{
    use MessageTrait;

    const OS_UNKNOWN = 'Unknown';
    const OS_WIN = 'Windows';
    const OS_LINUX = 'Linux';
    const OS_OSX = 'MacOS';

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

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
     * @var string
     */
    private string $bin_files_dir = '';

    /**
     * @var array
     */
    private array $extensions_with_polyfill = [
        'base58', 'ds', 'hrtime', 'inotify', 'oauth',
        'scrypt', 'simdjson', 'sodium', 'ssdeep', 'statistic (partial)',
        'translit', 'uopz (partial)', 'var_representation', 'xxtea',
        'xdiff (partial)', 'yaconf', 'yaml'
    ];

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();

        $this->PhpInfo();
        $this->dll_extensions_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "ext" . DIRECTORY_SEPARATOR;
        $this->bin_files_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR;
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
        $extension_name = $name . DIRECTORY_SEPARATOR . $name . '-' . $this->php_info['php_version'] . '-x64-' . $this->php_info['thread_safe'] . DIRECTORY_SEPARATOR . 'php_' . $name . '.dll';
        $component = $this->dll_extensions_dir . $extension_name;

        if (is_file($component)) {
            $component_name = basename($component);
            $component_to_ext_dir = $this->php_info['extensions_dir'] . $component_name;

            if (!is_file($component_to_ext_dir)) {
                $this->filesystem->copy($component, $component_to_ext_dir);
            }

            $this->statusExtension($name);
            return true;
        }

        $this->error($name . ': extension not found for your PHP version or (Non)Thread Safe')->print();
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
        if (PeclExtension::getOS() == 'Windows') {
            $ext = 'dll';
        }

        if (PeclExtension::getOS() == 'Linux') {
            $ext = 'so';
        }

        if (is_file($this->php_info['extensions_dir'] . 'php_' . $name . '.' . $ext)) {
            $this->success($name . ': extension installed')->print();
            $this->isExtensionEnable($name);

            return $this;
        }

        $this->warning($name . ': extension not installed')->print()->break();
        return $this;
    }

    /**
     * List binary files .exe available
     *
     * @return void
     */
    public function listWindowsServices(): void
    {
        $this->line('Binary files (.exe) available')->print()->break();

        $this->finder->directories()->in($this->bin_files_dir)->depth('==0');

        foreach ($this->finder as $finder) {
            $this->success('    ' . $finder->getBasename())->print()->break();
        }
    }

    /**
     * List polyfill and DLL available
     *
     * @return void
     */
    public function listExtensions(): void
    {
        $this->line('PECL Extensions with polyfill available')->print()->break();

        foreach ($this->extensions_with_polyfill as $ext) {
            $this->success('    ' . $ext)->print()->break();
        }

        echo PHP_EOL;

        if (PeclExtension::getOS() == 'Windows') {
            $this->line('PECL Extensions with DLL files')->print()->break();
            $this->info('Use \'vendor\bin\pecl install <extension_name>\'')->print()->break();

            $this->finder->directories()->in($this->dll_extensions_dir)->depth('==0');

            foreach ($this->finder as $finder) {
                $this->success('    ' . $finder->getBasename())->print()->break();
            }
        }
    }

    /**
     * Execute and kill a Windows process
     *
     * @param string $bin_name
     * @param object $option
     * 
     * @return true
     */
    public function serviceWindows(string $bin_name, object $option): true
    {
        $bin_dir = $this->bin_files_dir . $bin_name . DIRECTORY_SEPARATOR . $bin_name . '.exe';

        if (!is_file($bin_dir)) {
            $this->error($bin_name . ': binary (.exe) not found')->print()->exit();
        }

        if (isset($option->stop)) {
            $status = Process::status($bin_name, 'kill');

            if ($status == true) {
                $this->success($bin_name . ': process has been terminate')->print();
                return true;
            }
        }

        Process::execInBackground($bin_dir, 'process');
        $this->success($bin_name . ': process is in execution')->print();
        return true;
    }

    /**
     * Get OS used
     * 
     * @return int
     */
    public static function getOS()
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

    private function isExtensionEnable(string $name)
    {
        if (extension_loaded($name) == true) {
            $this->success(' and enabled on "php.ini"')->print()->break();
        } else {
            $this->success(', but not enabled. Add this line on your "php.ini"')->print()->break(true);
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
        switch ($name) {
            case 'apcu':
                $this->info('[apcu]')->print()->break();
                $this->info('extension=apcu')->print()->break();
                $this->info('apc.enabled=1')->print()->break();
                $this->info('apc.shm_size=32M')->print()->break();
                $this->info('apc.ttl=7200')->print()->break();
                $this->info('apc.enable_cli=1')->print()->break();
                $this->info('apc.serializer=php')->print();
                break;

            case 'pcov':
                $this->info('[pcov]')->print()->break();
                $this->info('extension=pcov')->print()->break();
                $this->info('pcov.enabled=1')->print()->break();
                $this->info('pcov.directory=/path/to/your/source/directory')->print();
                break;

            case 'imagick':
                $this->info('[imagick]')->print()->break();
                $this->info('extension=imagick')->print()->break();

                if (self::getOS() == 'Windows') {
                    echo PHP_EOL;
                    $this->success('See this link https://github.com/brenno-duarte/php-pecl-extensions/tree/main/ext-files/imagick/imagick-' . $this->php_info['php_version'] . '-x64-' . $this->php_info['thread_safe'] . '/')->print()->break();
                    $this->success('And copy all DLL files into the PHP installation directory (in the same directory as `php.exe`)')->print();
                }
                break;

            case 'yac':
                $this->info('[yac]')->print()->break();
                $this->info('extension=yac')->print()->break();
                $this->info('yac.enable_cli=1')->print();
                break;

            case 'xhprof':
                $this->info('[xhprof]')->print()->break();
                $this->info('extension=xhprof')->print()->break();
                $this->info('xhprof.output_dir=/tmp/xhprof')->print()->break();
                $this->info('xhprof.sampling_interval=100000')->print()->break();
                $this->info('xhprof.collect_additional_info=0')->print()->break(true);
                $this->success('See aditional files in https://github.com/brenno-duarte/php-pecl-extensions/tree/main/ext-files/xhprof/')->print();
                break;

            default:
                $this->info('extension=' . $name)->print();
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

        if (isset($array['PHP']['extension_dir'])) {
            $ext_dir = $array['PHP']['extension_dir'];
        } else {
            $ext_dir = dirname($ini_file) . DIRECTORY_SEPARATOR . 'ext' . DIRECTORY_SEPARATOR;
        }

        return $ext_dir;
    }
}
