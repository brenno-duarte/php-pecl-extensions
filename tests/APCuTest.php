<?php

use PHPUnit\Framework\TestCase;

class APCUTest extends TestCase
{
    public function testApcuIsEnabled()
    {
        $this->assertTrue(extension_loaded('apcu'));
    }
}
