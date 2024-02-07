<?php

namespace PeclPolyfillTest;

require_once dirname(__DIR__) . '/functions.php';

//use function scrypt;
use PHPUnit\Framework\TestCase;

class ScryptTest extends TestCase
{
    public function testScrypt()
    {
        //$this->assertFalse(extension_loaded('Scrypt'), 'Scrypt extension is loaded.');

        //if (extension_loaded('Scrypt')) return;

        $this->assertEquals(
            '95876fa32cc87e11b5b545dc0923864bd383d896055332b170ca1b79b2c8bb1dfe8e92956df3c44958c79eb9dd4b343327c6ff086aeb94a58c75cf02eb179887',
            scrypt('The quick brown fox jumps over the lazy dog', hex2bin('6c880a21'), 16384, 8, 8, 64)
        );
    }
}
