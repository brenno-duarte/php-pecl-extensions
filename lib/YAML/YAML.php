<?php

namespace PeclPolyfill\YAML;

use PeclPolyfill\YAML\Traits\{
    LoadFunctionsTrait,
    FormatterTrait,
    CompareTrait,
    ReturnElementsTrait
};

class YAML
{
    use LoadFunctionsTrait;
    use FormatterTrait;
    use CompareTrait;
    use ReturnElementsTrait;

    const REMPTY = "\0\0\0\0\0";

    /**
     * Setting this to true will force YAMLDump to enclose any string value in
     * quotes.  False by default.
     *
     * @var bool
     */
    public bool $setting_dump_force_quotes = false;

    /**
     * Setting this to true will forse YAMLLoad to use syckload function when
     * possible. False by default.
     * @var bool
     */
    public bool $setting_use_syck_is_possible = false;

    /**
     * Setting this to true will forse YAMLLoad to use syckload function when
     * possible. False by default.
     * @var bool
     */
    public bool $setting_empty_hash_as_object = false;

    /**#@+
     * @access private
     * @var mixed
     */
    private mixed $dumpIndent;
    private mixed $_dumpWordWrap;
    private bool $_containsGroupAnchor = false;
    private bool $_containsGroupAlias = false;
    private mixed $path;
    private mixed $result;
    private string $LiteralPlaceHolder = '___YAML_Literal_Block___';
    private mixed $SavedGroups = [];
    private string $indent;

    /**
     * Path modifier that should be applied after adding current element.
     * @var array
     */
    private array $delayedPath = [];

    /**
     * @var mixed
     */
    public mixed $_nodeId;

    /**
     * Load a valid YAML string to Spyc.
     * @param string $input
     * @return array
     */
    /* public function load(string $input): array
    {
        return $this->loadString($input);
    } */

    /**
     * Load a valid YAML file to Spyc.
     * @param string $file
     * @return array
     */
    public function loadFile($file)
    {
        return $this->load($file);
    }

    /**
     * Load YAML into a PHP array statically
     *
     * The load method, when supplied with a YAML stream (string or file),
     * will do its best to convert YAML in a file into a PHP array.  Pretty
     * simple.
     *  Usage:
     *  <code>
     *   $array = Spyc::YAMLLoad('lucky.yaml');
     *   print_r($array);
     *  </code>
     * @param string $input Path of YAML file or string containing YAML
     * @param array set options
     * 
     * @return array
     */
    public static function YAMLLoad(string $input, array $options = []): array
    {
        $Spyc = new YAML;
        foreach ($options as $key => $value) {
            if (property_exists($Spyc, $key)) {
                $Spyc->$key = $value;
            }
        }
        return $Spyc->load($input);
    }

    /**
     * Load a string of YAML into a PHP array statically
     *
     * The load method, when supplied with a YAML string, will do its best
     * to convert YAML in a string into a PHP array.  Pretty simple.
     *
     * Note: use this function if you don't want files from the file system
     * loaded and processed as YAML.  This is of interest to people concerned
     * about security whose input is from a string.
     *
     *  Usage:
     *  <code>
     *   $array = Spyc::YAMLLoadString("---\n0: hello world\n");
     *   print_r($array);
     *  </code>
     * @param string $input String containing YAML
     * @param array set options
     * 
     * @return array
     */
    public static function YAMLLoadString(string $input, array $options = []): array
    {
        $Spyc = new YAML;
        foreach ($options as $key => $value) {
            if (property_exists($Spyc, $key)) {
                $Spyc->$key = $value;
            }
        }
        return $Spyc->loadString($input);
    }

    /**
     * Dump YAML from PHP array statically
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.  Pretty simple.  Feel free to
     * save the returned string as nothing.yaml and pass it around.
     *
     * Oh, and you can decide how big the indent is and what the wordwrap
     * for folding is.  Pretty cool -- just pass in 'false' for either if
     * you want to use the default.
     *
     * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
     * you can turn off wordwrap by passing in 0.
     *
     * @param array|\stdClass $array PHP array
     * @param int $indent Pass in false to use the default, which is 2
     * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
     * @param bool $no_opening_dashes Do not start YAML file with "---\n"
     * 
     * @return string
     */
    public static function YAMLDump(
        array $array,
        int $indent = 2,
        int $wordwrap = 40,
        bool $no_opening_dashes = false
    ): string {
        $spyc = new YAML;
        return $spyc->dump($array, $indent, $wordwrap, $no_opening_dashes);
    }


    /**
     * Dump PHP array to YAML
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.  Pretty simple.  Feel free to
     * save the returned string as tasteful.yaml and pass it around.
     *
     * Oh, and you can decide how big the indent is and what the wordwrap
     * for folding is.  Pretty cool -- just pass in 'false' for either if
     * you want to use the default.
     *
     * Indent's default is 2 spaces, wordwrap's default is 40 characters.  And
     * you can turn off wordwrap by passing in 0.
     * @param array $array PHP array
     * @param int $indent Pass in false to use the default, which is 2
     * @param int $wordwrap Pass in 0 for no wordwrap, false for default (40)
     * 
     * @return string
     */
    public function dump(array $array, int $indent = 2, int $wordwrap = 40, bool $no_opening_dashes = false)
    {
        // Dumps to some very clean YAML.  We'll have to add some more features
        // and options soon.  And better support for folding.

        // New features and options.
        if ($indent === false or !is_numeric($indent)) {
            $this->dumpIndent = 2;
        } else {
            $this->dumpIndent = $indent;
        }

        if ($wordwrap === false or !is_numeric($wordwrap)) {
            $this->_dumpWordWrap = 40;
        } else {
            $this->_dumpWordWrap = $wordwrap;
        }

        // New YAML document
        $string = "";
        if (!$no_opening_dashes) $string = "---\n";

        // Start at the base of the array and move through it.
        if ($array) {
            $array = (array)$array;
            $previous_key = -1;

            foreach ($array as $key => $value) {
                if (!isset($first_key)) $first_key = $key;
                $string .= $this->yamlize($key, $value, 0, $previous_key, $first_key, $array);
                $previous_key = $key;
            }
        }

        return $string;
    }

    /**
     * Attempts to convert a key / value array item to YAML
     * 
     * @param $key The name of the key
     * @param $value The value of the item
     * @param $indent The indent of the current node
     * 
     * @return string
     */
    private function yamlize(
        string $key,
        mixed $value,
        string $indent,
        mixed $previous_key = -1,
        mixed $first_key = 0,
        ?array $source_array = null
    ) {
        if (is_object($value)) $value = (array)$value;
        if (is_array($value)) {
            if (empty($value))
                return $this->dumpNode($key, [], $indent, $previous_key, $first_key, $source_array);
            // It has children.  What to do?
            // Make it the right kind of item
            $string = $this->dumpNode($key, self::REMPTY, $indent, $previous_key, $first_key, $source_array);
            // Add the indent
            $indent += $this->dumpIndent;
            // Yamlize the array
            $string .= $this->yamlizeArray($value, $indent);
        } elseif (!is_array($value)) {
            // It doesn't have children.  Yip.
            $string = $this->dumpNode($key, $value, $indent, $previous_key, $first_key, $source_array);
        }
        return $string;
    }

    /**
     * Attempts to convert an array to YAML
     * @access private
     * @return string
     * @param $array The array you want to convert
     * @param $indent The indent of the current level
     */
    private function yamlizeArray($array, $indent)
    {
        if (is_array($array)) {
            $string = '';
            $previous_key = -1;
            foreach ($array as $key => $value) {
                if (!isset($first_key)) $first_key = $key;
                $string .= $this->yamlize($key, $value, $indent, $previous_key, $first_key, $array);
                $previous_key = $key;
            }
            return $string;
        } else {
            return false;
        }
    }

    /**
     * Returns YAML from a key and a value
     * 
     * @param string  $key The name of the key
     * @param string|array $value The value of the item
     * @param string $indent The indent of the current node
     * 
     * @return string
     */
    private function dumpNode(
        string $key,
        string|array $value,
        string $indent,
        mixed $previous_key = -1,
        mixed $first_key = 0,
        ?array $source_array = null
    ) {
        // do some folding here, for blocks
        if (
            is_string($value) && ((strpos($value, "\n") !== false || strpos($value, ": ") !== false || strpos($value, "- ") !== false ||
                strpos($value, "*") !== false || strpos($value, "#") !== false || strpos($value, "<") !== false || strpos($value, ">") !== false || strpos($value, '%') !== false || strpos($value, '  ') !== false ||
                strpos($value, "[") !== false || strpos($value, "]") !== false || strpos($value, "{") !== false || strpos($value, "}") !== false) || strpos($value, "&") !== false || strpos($value, "'") !== false || strpos($value, "!") === 0 ||
                substr($value, -1, 1) == ':')
        ) {
            $value = $this->doLiteralBlock($value, $indent);
        } else {
            $value  = $this->doFolding($value, $indent);
        }

        if ($value === []) $value = '[ ]';
        if ($value === "") $value = '""';
        if (self::isTranslationWord($value)) {
            $value = $this->doLiteralBlock($value, $indent);
        }
        if (trim($value) != $value)
            $value = $this->doLiteralBlock($value, $indent);

        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }

        if ($value === null) $value = 'null';
        if ($value === "'" . self::REMPTY . "'") $value = null;

        $spaces = str_repeat(' ', $indent);

        //if (is_int($key) && $key - 1 == $previous_key && $first_key===0) {
        if (is_array($source_array) && array_keys($source_array) === range(0, count($source_array) - 1)) {
            // It's a sequence
            $string = $spaces . '- ' . $value . "\n";
        } else {
            // if ($first_key===0)  throw new Exception('Keys are all screwy.  The first one was zero, now it\'s "'. $key .'"');
            // It's mapped
            if (strpos($key, ":") !== false || strpos($key, "#") !== false) {
                $key = '"' . $key . '"';
            }
            $string = rtrim($spaces . $key . ': ' . $value) . "\n";
        }
        return $string;
    }

    /**
     * Creates a literal block for dumping
     * 
     * @param $value
     * @param $indent int The value of the indent
     * 
     * @return string
     */
    private function doLiteralBlock(string $value, string $indent): string
    {
        if ($value === "\n") return '\n';
        if (strpos($value, "\n") === false && strpos($value, "'") === false) {
            return sprintf("'%s'", $value);
        }
        if (strpos($value, "\n") === false && strpos($value, '"') === false) {
            return sprintf('"%s"', $value);
        }
        $exploded = explode("\n", $value);
        $newValue = '|';
        if (isset($exploded[0]) && ($exploded[0] == "|" || $exploded[0] == "|-" || $exploded[0] == ">")) {
            $newValue = $exploded[0];
            unset($exploded[0]);
        }
        $indent += $this->dumpIndent;
        $spaces   = str_repeat(' ', $indent);
        foreach ($exploded as $line) {
            $line = trim($line);
            if (strpos($line, '"') === 0 && strrpos($line, '"') == (strlen($line) - 1) || strpos($line, "'") === 0 && strrpos($line, "'") == (strlen($line) - 1)) {
                $line = substr($line, 1, -1);
            }
            $newValue .= "\n" . $spaces . ($line);
        }
        return $newValue;
    }

    /**
     * Folds a string of text, if necessary
     * 
     * @param $value The string you wish to fold
     * 
     * @return string
     */
    private function doFolding(mixed $value, string $indent)
    {
        // Don't do anything if wordwrap is set to 0

        if ($this->_dumpWordWrap !== 0 && is_string($value) && strlen($value) > $this->_dumpWordWrap) {
            $indent += $this->dumpIndent;
            $indent = str_repeat(' ', $indent);
            $wrapped = wordwrap($value, $this->_dumpWordWrap, "\n$indent");
            $value   = ">\n" . $indent . $wrapped;
        } else {
            if ($this->setting_dump_force_quotes && is_string($value) && $value !== self::REMPTY)
                $value = '"' . $value . '"';
            if (is_numeric($value) && is_string($value))
                $value = '"' . $value . '"';
        }


        return $value;
    }

    /**
     * Given a set of words, perform the appropriate translations on them to
     * match the YAML 1.1 specification for type coercing.
     * @param array $words The words to translate
     * 
     * @return array
     */
    private static function getTranslations(array $words): array
    {
        $result = [];
        foreach ($words as $i) {
            $result = array_merge($result, array(ucfirst($i), strtoupper($i), strtolower($i)));
        }
        return $result;
    }

    /**
     * Parses YAML code and returns an array for a node
     * @access private
     * @return array
     * @param string $line A line from the YAML file
     */
    private function parseLine($line)
    {
        if (!$line) return [];
        $line = trim($line);
        if (!$line) return [];

        $array = [];

        $group = $this->nodeContainsGroup($line);
        if ($group) {
            $this->addGroup($line, $group);
            $line = $this->stripGroup($line, $group);
        }

        if ($this->startsMappedSequence($line)) {
            return $this->returnMappedSequence($line);
        }

        if ($this->startsMappedValue($line)) {
            return $this->returnMappedValue($line);
        }

        if ($this->isArrayElement($line))
            return $this->returnArrayElement($line);

        if ($this->isPlainArray($line))
            return $this->returnPlainArray($line);

        return $this->returnKeyValuePair($line);
    }

    private function getParentPathByIndent($indent)
    {
        if ($indent == 0) return array();
        $linePath = $this->path;
        do {
            end($linePath);
            $lastIndentInParentPath = key($linePath);
            if ($indent <= $lastIndentInParentPath) array_pop($linePath);
        } while ($indent <= $lastIndentInParentPath);
        return $linePath;
    }

    private static function unquote($value)
    {
        if (!$value) return $value;
        if (!is_string($value)) return $value;
        if ($value[0] == '\'') return trim($value, '\'');
        if ($value[0] == '"') return trim($value, '"');
        return $value;
    }

    private function startsMappedSequence($line)
    {
        return (substr($line, 0, 2) == '- ' && substr($line, -1, 1) == ':');
    }

    private function returnMappedSequence($line)
    {
        $array = array();
        $key         = self::unquote(trim(substr($line, 1, -1)));
        $array[$key] = array();
        $this->delayedPath = array(strpos($line, $key) + $this->indent => $key);
        return array($array);
    }

    private function checkKeysInValue($value)
    {
        if (strchr('[{"\'', $value[0]) === false) {
            if (strchr($value, ': ') !== false) {
                throw new \Exception('Too many keys: ' . $value);
            }
        }
    }

    private function startsMappedValue($line)
    {
        return (substr($line, -1, 1) == ':');
    }

    private function nodeContainsGroup($line)
    {
        $symbolsForReference = 'A-z0-9_\-';
        if (strpos($line, '&') === false && strpos($line, '*') === false) return false; // Please die fast ;-)
        if ($line[0] == '&' && preg_match('/^(&[' . $symbolsForReference . ']+)/', $line, $matches)) return $matches[1];
        if ($line[0] == '*' && preg_match('/^(\*[' . $symbolsForReference . ']+)/', $line, $matches)) return $matches[1];
        if (preg_match('/(&[' . $symbolsForReference . ']+)$/', $line, $matches)) return $matches[1];
        if (preg_match('/(\*[' . $symbolsForReference . ']+$)/', $line, $matches)) return $matches[1];
        if (preg_match('#^\s*<<\s*:\s*(\*[^\s]+).*$#', $line, $matches)) return $matches[1];
        return false;
    }

    private function addGroup($line, $group)
    {
        if ($group[0] == '&') $this->_containsGroupAnchor = substr($group, 1);
        if ($group[0] == '*') $this->_containsGroupAlias = substr($group, 1);
        //print_r ($this->path);
    }

    private function stripGroup($line, $group)
    {
        $line = trim(str_replace($group, '', $line));
        return $line;
    }
}
