<?php

namespace PeclPolyfillTest;

use PeclPolyfill\Functions\XXTEA\XXTEA;
use PHPUnit\Framework\TestCase;

class XXTEATest extends TestCase
{
    public function testEncrypt()
    {
        $str = "Hello World! 你好，中国🇨🇳！";
        $key = "1234567890";
        $encrypt_data = XXTEA::encrypt($str, $key);
        $this->assertEquals(base64_encode($encrypt_data), "D4t0rVXUDl3bnWdERhqJmFIanfn/6zAxAY9jD6n9MSMQNoD8TOS4rHHcGuE=");
    }

    public function testDecrypt()
    {
        $str = "Hello World! 你好，中国🇨🇳！";
        $key = "1234567890";
        $encrypt_data = XXTEA::encrypt($str, $key);
        $decrypt_data = XXTEA::decrypt($encrypt_data, $key);
        $this->assertEquals($decrypt_data, $str);
    }
}
