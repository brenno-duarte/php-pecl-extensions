<?php

namespace PeclPolyfill\Functions\YAML\Traits;

trait FormatterTrait
{
    /**
     * Used in inlines to check for more inlines or quoted strings
     * @access private
     * @return array
     */
    private function inlineEscape($inline)
    {
        // There's gotta be a cleaner way to do this...
        // While pure sequences seem to be nesting just fine,
        // pure mappings and mappings with sequences inside can't go very
        // deep.  This needs to be fixed.

        $seqs = [];
        $maps = [];
        $saved_strings = [];
        $saved_empties = [];

        // Check for empty strings
        $regex = '/("")|(\'\')/';
        if (preg_match_all($regex, $inline, $strings)) {
            $saved_empties = $strings[0];
            $inline  = preg_replace($regex, 'YAMLEmpty', $inline);
        }
        unset($regex);

        // Check for strings
        $regex = '/(?:(")|(?:\'))((?(1)[^"]+|[^\']+))(?(1)"|\')/';
        if (preg_match_all($regex, $inline, $strings)) {
            $saved_strings = $strings[0];
            $inline  = preg_replace($regex, 'YAMLString', $inline);
        }
        unset($regex);

        // echo $inline;

        $i = 0;
        do {

            // Check for sequences
            while (preg_match('/\[([^{}\[\]]+)\]/U', $inline, $matchseqs)) {
                $seqs[] = $matchseqs[0];
                $inline = preg_replace('/\[([^{}\[\]]+)\]/U', ('YAMLSeq' . (count($seqs) - 1) . 's'), $inline, 1);
            }

            // Check for mappings
            while (preg_match('/{([^\[\]{}]+)}/U', $inline, $matchmaps)) {
                $maps[] = $matchmaps[0];
                $inline = preg_replace('/{([^\[\]{}]+)}/U', ('YAMLMap' . (count($maps) - 1) . 's'), $inline, 1);
            }

            if ($i++ >= 10) break;
        } while (strpos($inline, '[') !== false || strpos($inline, '{') !== false);

        $explode = explode(',', $inline);
        $explode = array_map('trim', $explode);
        $stringi = 0;
        $i = 0;

        while (1) {

            // Re-add the sequences
            if (!empty($seqs)) {
                foreach ($explode as $key => $value) {
                    if (strpos($value, 'YAMLSeq') !== false) {
                        foreach ($seqs as $seqk => $seq) {
                            $explode[$key] = str_replace(('YAMLSeq' . $seqk . 's'), $seq, $value);
                            $value = $explode[$key];
                        }
                    }
                }
            }

            // Re-add the mappings
            if (!empty($maps)) {
                foreach ($explode as $key => $value) {
                    if (strpos($value, 'YAMLMap') !== false) {
                        foreach ($maps as $mapk => $map) {
                            $explode[$key] = str_replace(('YAMLMap' . $mapk . 's'), $map, $value);
                            $value = $explode[$key];
                        }
                    }
                }
            }


            // Re-add the strings
            if (!empty($saved_strings)) {
                foreach ($explode as $key => $value) {
                    while (strpos($value, 'YAMLString') !== false) {
                        $explode[$key] = preg_replace('/YAMLString/', $saved_strings[$stringi], $value, 1);
                        unset($saved_strings[$stringi]);
                        ++$stringi;
                        $value = $explode[$key];
                    }
                }
            }


            // Re-add the empties
            if (!empty($saved_empties)) {
                foreach ($explode as $key => $value) {
                    while (strpos($value, 'YAMLEmpty') !== false) {
                        $explode[$key] = preg_replace('/YAMLEmpty/', '', $value, 1);
                        $value = $explode[$key];
                    }
                }
            }

            $finished = true;
            foreach ($explode as $key => $value) {
                if (strpos($value, 'YAMLSeq') !== false) {
                    $finished = false;
                    break;
                }
                if (strpos($value, 'YAMLMap') !== false) {
                    $finished = false;
                    break;
                }
                if (strpos($value, 'YAMLString') !== false) {
                    $finished = false;
                    break;
                }
                if (strpos($value, 'YAMLEmpty') !== false) {
                    $finished = false;
                    break;
                }
            }
            if ($finished) break;

            $i++;
            if ($i > 10)
                break; // Prevent infinite loops.
        }


        return $explode;
    }

    private function literalBlockContinues($line, $lineIndent)
    {
        if (!trim($line)) return true;
        if (strlen($line) - strlen(ltrim($line)) > $lineIndent) return true;
        return false;
    }

    private function referenceContentsByAlias($alias)
    {
        do {
            if (!isset($this->SavedGroups[$alias])) {
                echo "Bad group name: $alias.";
                break;
            }
            $groupPath = $this->SavedGroups[$alias];
            $value = $this->result;
            foreach ($groupPath as $k) {
                $value = $value[$k];
            }
        } while (false);
        return $value;
    }

    private function addArrayInline($array, $indent)
    {
        $CommonGroupPath = $this->path;
        if (empty($array)) return false;

        foreach ($array as $k => $_) {
            $this->addArray(array($k => $_), $indent);
            $this->path = $CommonGroupPath;
        }
        return true;
    }

    private function addArray($incoming_data, $incoming_indent)
    {

        // print_r ($incoming_data);

        if (count($incoming_data) > 1)
            return $this->addArrayInline($incoming_data, $incoming_indent);

        $key = key($incoming_data);
        $value = isset($incoming_data[$key]) ? $incoming_data[$key] : null;
        if ($key === '__!YAMLZero') $key = '0';

        if ($incoming_indent == 0 && !$this->_containsGroupAlias && !$this->_containsGroupAnchor) { // Shortcut for root-level values.
            if ($key || $key === '' || $key === '0') {
                $this->result[$key] = $value;
            } else {
                $this->result[] = $value;
                end($this->result);
                $key = key($this->result);
            }
            $this->path[$incoming_indent] = $key;
            return;
        }



        $history = [];
        // Unfolding inner array tree.
        $history[] = $_arr = $this->result;
        foreach ($this->path as $k) {
            $history[] = $_arr = $_arr[$k];
        }

        if ($this->_containsGroupAlias) {
            $value = $this->referenceContentsByAlias($this->_containsGroupAlias);
            $this->_containsGroupAlias = false;
        }


        // Adding string or numeric key to the innermost level or $this->arr.
        if (is_string($key) && $key == '<<') {
            if (!is_array($_arr)) {
                $_arr = [];
            }

            $_arr = array_merge($_arr, $value);
        } else if ($key || $key === '' || $key === '0') {
            if (!is_array($_arr))
                $_arr = array($key => $value);
            else
                $_arr[$key] = $value;
        } else {
            if (!is_array($_arr)) {
                $_arr = array($value);
                $key = 0;
            } else {
                $_arr[] = $value;
                end($_arr);
                $key = key($_arr);
            }
        }

        $reverse_path = array_reverse($this->path);
        $reverse_history = array_reverse($history);
        $reverse_history[0] = $_arr;
        $cnt = count($reverse_history) - 1;
        for ($i = 0; $i < $cnt; $i++) {
            $reverse_history[$i + 1][$reverse_path[$i]] = $reverse_history[$i];
        }
        $this->result = $reverse_history[$cnt];

        $this->path[$incoming_indent] = $key;

        if ($this->_containsGroupAnchor) {
            $this->SavedGroups[$this->_containsGroupAnchor] = $this->path;
            if (is_array($value)) {
                $k = key($value);
                if (!is_int($k)) {
                    $this->SavedGroups[$this->_containsGroupAnchor][$incoming_indent + 2] = $k;
                }
            }
            $this->_containsGroupAnchor = false;
        }
    }

    /**
     * @param string $line
     * @return string
     */
    private static function startsLiteralBlock(string $line): string
    {
        $lastChar = substr(trim($line), -1);
        if ($lastChar != '>' && $lastChar != '|') return false;
        if ($lastChar == '|') return $lastChar;
        // HTML tags should not be counted as literal blocks.
        if (preg_match('#<.*?>$#', $line)) return false;
        return $lastChar;
    }

    /**
     * @param string $line
     * @return string
     */
    private static function greedilyNeedNextLine(string $line): string
    {
        $line = trim($line);
        if (!strlen($line)) return false;
        if (substr($line, -1, 1) == ']') return false;
        if ($line[0] == '[') return true;
        if (preg_match('#^[^:]+?:\s*\[#', $line)) return true;
        return false;
    }

    private function addLiteralLine(
        string $literalBlock,
        string $line,
        string $literalBlockStyle,
        int $indent = -1
    ): string {
        $line = self::stripIndent($line, $indent);
        if ($literalBlockStyle !== '|') {
            $line = self::stripIndent($line);
        }
        $line = rtrim($line, "\r\n\t ") . "\n";
        if ($literalBlockStyle == '|') {
            return $literalBlock . $line;
        }
        if (strlen($line) == 0)
            return rtrim($literalBlock, ' ') . "\n";
        if ($line == "\n" && $literalBlockStyle == '>') {
            return rtrim($literalBlock, " \t") . "\n";
        }
        if ($line != "\n")
            $line = trim($line, "\r\n ") . " ";
        return $literalBlock . $line;
    }

    private function revertLiteralPlaceHolder($lineArray, $literalBlock)
    {
        foreach ($lineArray as $k => $_) {
            if (is_array($_))
                $lineArray[$k] = $this->revertLiteralPlaceHolder($_, $literalBlock);
            else if (substr($_, -1 * strlen($this->LiteralPlaceHolder)) == $this->LiteralPlaceHolder)
                $lineArray[$k] = rtrim($literalBlock, " \r\n");
        }
        return $lineArray;
    }

    private static function stripIndent($line, $indent = -1)
    {
        if ($indent == -1) $indent = strlen($line) - strlen(ltrim($line));
        return substr($line, $indent);
    }

    private function clearBiggerPathValues($indent)
    {
        if ($indent == 0) $this->path = array();
        if (empty($this->path)) return true;

        foreach ($this->path as $k => $_) {
            if ($k > $indent) unset($this->path[$k]);
        }

        return true;
    }
}
