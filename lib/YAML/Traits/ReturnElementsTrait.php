<?php

namespace PeclPolyfill\YAML\Traits;

trait ReturnElementsTrait
{
    /**
     * Finds the type of the passed value, returns the value as the new type.
     * @access private
     * @param string $value
     * @return mixed
     */
    private function toType($value)
    {
        if ($value === '') return "";

        if ($this->setting_empty_hash_as_object && $value === '{}') {
            return new \stdClass();
        }

        $first_character = $value[0];
        $last_character = substr($value, -1, 1);

        $is_quoted = false;
        do {
            if (!$value) break;
            if ($first_character != '"' && $first_character != "'") break;
            if ($last_character != '"' && $last_character != "'") break;
            $is_quoted = true;
        } while (0);

        if ($is_quoted) {
            $value = str_replace('\n', "\n", $value);
            if ($first_character == "'")
                return strtr(substr($value, 1, -1), array('\'\'' => '\'', '\\\'' => '\''));
            return strtr(substr($value, 1, -1), array('\\"' => '"', '\\\'' => '\''));
        }

        if (strpos($value, ' #') !== false && !$is_quoted)
            $value = preg_replace('/\s+#(.+)$/', '', $value);

        if ($first_character == '[' && $last_character == ']') {
            // Take out strings sequences and mappings
            $innerValue = trim(substr($value, 1, -1));
            if ($innerValue === '') return array();
            $explode = $this->inlineEscape($innerValue);
            // Propagate value array
            $value  = [];
            foreach ($explode as $v) {
                $value[] = $this->toType($v);
            }
            return $value;
        }

        if (strpos($value, ': ') !== false && $first_character != '{') {
            $array = explode(': ', $value);
            $key   = trim($array[0]);
            array_shift($array);
            $value = trim(implode(': ', $array));
            $value = $this->toType($value);
            return array($key => $value);
        }

        if ($first_character == '{' && $last_character == '}') {
            $innerValue = trim(substr($value, 1, -1));
            if ($innerValue === '') return array();
            // Inline Mapping
            // Take out strings sequences and mappings
            $explode = $this->inlineEscape($innerValue);
            // Propagate value array
            $array = array();
            foreach ($explode as $v) {
                $SubArr = $this->toType($v);
                if (empty($SubArr)) continue;
                if (is_array($SubArr)) {
                    $array[key($SubArr)] = $SubArr[key($SubArr)];
                    continue;
                }
                $array[] = $SubArr;
            }
            return $array;
        }

        if ($value == 'null' || $value == 'NULL' || $value == 'Null' || $value == '' || $value == '~') {
            return null;
        }

        if (is_numeric($value) && preg_match('/^(-|)[1-9]+[0-9]*$/', $value)) {
            $intvalue = (int)$value;
            if ($intvalue != PHP_INT_MAX && $intvalue != ~PHP_INT_MAX)
                $value = $intvalue;
            return $value;
        }

        if (is_string($value) && preg_match('/^0[xX][0-9a-fA-F]+$/', $value)) {
            // Hexadecimal value.
            return hexdec($value);
        }

        $this->coerceValue($value);

        if (is_numeric($value)) {
            if ($value === '0') return 0;
            if (rtrim($value, 0) === $value)
                $value = (float)$value;
            return $value;
        }

        return $value;
    }
    
    private function returnMappedValue($line)
    {
        $this->checkKeysInValue($line);
        $array = array();
        $key         = self::unquote(trim(substr($line, 0, -1)));
        $array[$key] = '';
        return $array;
    }
    
    private function returnPlainArray($line)
    {
        return $this->toType($line);
    }

    private function returnKeyValuePair($line)
    {
        $array = [];
        $key = '';
        if (strpos($line, ': ')) {
            // It's a key/value pair most likely
            // If the key is in double quotes pull it out
            if (($line[0] == '"' || $line[0] == "'") && preg_match('/^(["\'](.*)["\'](\s)*:)/', $line, $matches)) {
                $value = trim(str_replace($matches[1], '', $line));
                $key   = $matches[2];
            } else {
                // Do some guesswork as to the key and the value
                $explode = explode(': ', $line);
                $key     = trim(array_shift($explode));
                $value   = trim(implode(': ', $explode));
                $this->checkKeysInValue($value);
            }
            // Set the type of the value.  Int, string, etc
            $value = $this->toType($value);

            if ($key === '0') $key = '__!YAMLZero';
            $array[$key] = $value;
        } else {
            $array = array($line);
        }
        return $array;
    }

    private function returnArrayElement($line)
    {
        if (strlen($line) <= 1) return array([]); // Weird %)
        $array = [];
        $value   = trim(substr($line, 1));
        $value   = $this->toType($value);
        if ($this->isArrayElement($value)) {
            $value = $this->returnArrayElement($value);
        }
        $array[] = $value;
        return $array;
    }


}
