<?php

use PHPUnit\Framework\TestCase;
use Yaconf\Yaconf;

class YaconfTest extends TestCase
{
    public function testGet()
    {
        Yaconf::iniFile(dirname(__DIR__). "/tests/foo.ini");
        
        $this->assertEquals("val", Yaconf::get("foo.SectionA.key"));
        $this->assertEquals("new_val", Yaconf::get("foo.SectionB.key"));
        $this->assertEquals("first_key", Yaconf::get("foo.key"));
    }

    public function testHas()
    {
        Yaconf::iniFile(dirname(__DIR__). "/tests/foo.ini");
        
        $this->assertTrue(Yaconf::has("foo.SectionA.key"));
        $this->assertFalse(Yaconf::has("foo.notexists"));
    }
}
