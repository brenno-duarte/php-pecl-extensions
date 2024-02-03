<?php

require_once 'vendor/autoload.php';

use PeclPolyfill\YAML\{YAML, YamlException};

if (!extension_loaded('yaml')) {
    function yaml_parse(string $input): mixed
    {
        if (is_file($input)) {
            throw new YamlException("File found. Use \"yaml_parse_file\"");
        }

        return YAML::YAMLLoad($input);
    }

    function yaml_parse_file(string $input): mixed
    {
        if (!is_file($input)) {
            throw new YamlException("String found. Use \"yaml_parse\"");
        }

        return YAML::YAMLLoad($input);
    }

    function yaml_emit(array $input): mixed
    {
        return YAML::YAMLDump($input);
    }
}