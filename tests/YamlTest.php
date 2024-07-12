<?php

require_once dirname(__DIR__) . "/functions.php";

use PHPUnit\Framework\TestCase;

class YamlTest extends TestCase
{
    public function testParse()
    {
        $yaml = <<<EOD
            invoice: 34843
            date: "2001-01-23"
            comments: Late afternoon is best. Backup contact is Nancy Billsmer @ 338-4338.
            EOD;

        $this->assertEquals([
            "invoice" => 34843,
            "date" => "2001-01-23",
            "comments" => "Late afternoon is best. Backup contact is Nancy Billsmer @ 338-4338."
        ], yaml_parse($yaml));
    }
}
