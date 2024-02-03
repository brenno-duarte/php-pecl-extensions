<?php

namespace PeclPolyfill\Yaconf;

use Matomo\Ini\IniReader;

class Yaconf
{
    /**
     * @var string
     */
    private static string $file_name = '';

    /**
     * @param string $file_name
     * 
     * @return void
     */
    public static function iniFile(string $file_name): void
    {
        $file_name = str_replace("/", DIRECTORY_SEPARATOR, $file_name);
        self::$file_name = $file_name;
    }

    public static function get(string $name, mixed $default_value = null): mixed
    {
        list($filename, $key) = explode('.', $name, 2);

        if (self::$file_name == '') {
            self::$file_name = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $filename . '.ini';
        }

        return self::handle($key, $default_value);
    }

    /**
     * @param string $name
     * 
     * @return bool
     */
    public static function has(string $name): bool
    {
        list($filename, $key) = explode('.', $name, 2);

        if (self::$file_name == '') {
            self::$file_name = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $filename . '.ini';
        }

        $res = self::handle($key);

        if (!is_null($res)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $key
     * @param mixed|null $default_value
     * 
     * @return mixed
     */
    private static function handle(mixed $key, mixed $default_value = null): mixed
    {
        if (is_file(self::$file_name) && is_readable(self::$file_name)) {
            $reader = new IniReader();
            $array = $reader->readFile(self::$file_name);
            $key_ini = explode(".", $key);
            $key_find = end($key_ini);
            $final_value = null;

            foreach ($array as $key => $value) {
                if ($key == $key_ini[0]) {
                    if (is_array($value)) {
                        $final_value = $value[$key_find];
                    }

                    if (is_string($value)) {
                        $final_value = $value;
                    }

                    break;
                }

                if (str_contains($key, $key_ini[0])) {
                    $final_value = $value[$key_find];
                    break;
                }
            }

            return $final_value;
        }

        return $default_value;
    }
}
