<?php

use PeclPolyfill\Functions\Base58\Base58;
use PeclPolyfill\Functions\Base58\BCMathService;
use PeclPolyfill\Functions\Base58\GMPService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class Base58Tests extends TestCase
{
    public function setUp(): void
    {
        if (!extension_loaded('gmp')) {
            throw new \Exception("`gmp` extension isn't enabled");
        }
    }

    #[DataProvider("encodingsProvider")]
    public function testEncode($string, $encoded, $instance)
    {
        $string = (string) $string;
        $encoded = (string) $encoded;

        $this->assertSame($encoded, $instance->encode($string));
    }

    #[DataProvider("encodingsProvider")]
    public function testDecode($string, $encoded, $instance)
    {
        $string = (string) $string;
        $encoded = (string) $encoded;

        $this->assertSame($string, $instance->decode($encoded));
    }

    public static function encodingsProvider()
    {
        $instances = [
            new Base58(null, new BCMathService()),
            new Base58(null, new GMPService())
        ];

        $tests = [
            ['', ''],
            ['1', 'r'],
            ['a', '2g'],
            ['bbb', 'a3gV'],
            ['ccc', 'aPEr'],
            ['hello!', 'tzCkV5Di'],
            ['Hello World', 'JxF12TrwUP45BMd'],
            ['this is a test', 'jo91waLQA1NNeBmZKUF'],
            ['the quick brown fox', 'NK2qR8Vz63NeeAJp9XRifbwahu'],
            ['THE QUICK BROWN FOX', 'GRvKwF9B69ssT67JgRWxPQTZ2X'],
            ['simply a long string', '2cFupjhnEsSn59qHXstmK2ffpLv2'],
            ["\x00\x61", '12g'],
            ["\x00", '1'],
            ["\x00\x00", '11'],
            ['0248ac9d3652ccd8350412b83cb08509e7e4bd41', '3PtvAWwSMPe2DohNuCFYy76JhMV3rhxiSxQMbPBTtiPvYvneWu95XaY']
        ];

        $return = [];

        foreach ($instances as $instance) {
            foreach ($tests as $test) {
                $test[] = $instance;
                $return[] = $test;
            }
        }

        return $return;
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Argument $alphabet must be a string.
     */
    public function testConstructorTypeException()
    {
        $this->expectException("InvalidArgumentException");
        new Base58(intval(123));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Argument $alphabet must contain 58 characters.
     */
    public function testConstructorLengthException()
    {
        $this->expectException("InvalidArgumentException");
        new Base58('');
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Argument $string must be a string.
     */
    public function testEncodeTypeException()
    {
        //$this->expectException('InvalidArgumentException');
        $base58 = new Base58();
        $base58->encode(intval(123));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Argument $base58 must be a string.
     */
    public function testDecodeTypeException()
    {
        //$this->expectException('InvalidArgumentException');
        $base58 = new Base58();
        $base58->decode(intval(123));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Argument $base58 contains invalid characters.
     */
    public function testInvalidBase58()
    {
        $this->expectException("InvalidArgumentException");
        $base58 = new Base58();
        $base58->decode("This isn't valid base58");
    }
}