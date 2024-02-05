<?php

namespace PeclPolyfill\Functions\YAML\Traits;

trait LoadFunctionsTrait
{
    private function load($input)
    {
        $Source = $this->loadFromSource($input);
        return $this->loadWithSource($Source);
    }

    private function loadString($input)
    {
        $Source = $this->loadFromString($input);
        return $this->loadWithSource($Source);
    }

    private function loadWithSource(array $Source)
    {
        if (empty($Source)) return [];
        if ($this->setting_use_syck_is_possible && function_exists('syckload')) {
            $array = self::YAMLLoadString(implode("\n", $Source));
            return is_array($array) ? $array : [];
        }

        $this->path = [];
        $this->result = [];

        $cnt = count($Source);
        for ($i = 0; $i < $cnt; $i++) {
            $line = $Source[$i];

            $this->indent = strlen($line) - strlen(ltrim($line));
            $tempPath = $this->getParentPathByIndent($this->indent);
            $line = self::stripIndent($line, $this->indent);
            if (self::isComment($line)) continue;
            if (self::isEmpty($line)) continue;
            $this->path = $tempPath;

            $literalBlockStyle = self::startsLiteralBlock($line);
            if ($literalBlockStyle) {
                $line = rtrim($line, $literalBlockStyle . " \n");
                $literalBlock = '';
                $line .= ' ' . $this->LiteralPlaceHolder;
                $literal_block_indent = strlen($Source[$i + 1]) - strlen(ltrim($Source[$i + 1]));
                while (++$i < $cnt && $this->literalBlockContinues($Source[$i], $this->indent)) {
                    $literalBlock = $this->addLiteralLine($literalBlock, $Source[$i], $literalBlockStyle, $literal_block_indent);
                }
                $i--;
            }

            // Strip out comments
            if (strpos($line, '#')) {
                $line = preg_replace('/\s*#([^"\']+)$/', '', $line);
            }

            while (++$i < $cnt && self::greedilyNeedNextLine($line)) {
                $line = rtrim($line, " \n\t\r") . ' ' . ltrim($Source[$i], " \t");
            }
            $i--;

            $lineArray = $this->parseLine($line);

            if ($literalBlockStyle)
                $lineArray = $this->revertLiteralPlaceHolder($lineArray, $literalBlock);

            $this->addArray($lineArray, $this->indent);

            foreach ($this->delayedPath as $indent => $delayedPath)
                $this->path[$indent] = $delayedPath;

            $this->delayedPath = [];
        }
        return $this->result;
    }

    private function loadFromSource($input)
    {
        if (!empty($input) && strpos($input, "\n") === false && file_exists($input))
            $input = file_get_contents($input);

        return $this->loadFromString($input);
    }

    private function loadFromString($input)
    {
        $lines = explode("\n", $input);
        foreach ($lines as $k => $_) {
            $lines[$k] = rtrim($_, "\r");
        }
        return $lines;
    }
}
