<?php

use PHPUnit\Framework\TestCase;

class SsdeepTest extends TestCase
{
    public function testHash()
    {
        $hash1 = ssdeep_fuzzy_hash("hash");
        $hash2 = ssdeep_fuzzy_hash("hash");

        $this->assertEquals(100, ssdeep_fuzzy_compare($hash1, $hash2));
        
        $hash1 = ssdeep_fuzzy_hash("hash");
        $hash2 = ssdeep_fuzzy_hash("hashother");

        $this->assertEquals(20, ssdeep_fuzzy_compare($hash1, $hash2));
    }
}
