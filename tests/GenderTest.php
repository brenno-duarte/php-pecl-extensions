<?php

namespace PeclPolyfillTest;

use PeclPolyfill\Gender\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    public function testJohn()
    {
        $gender = new Gender();
        $name = "John";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_MALE, $result);
    }

    public function testGrace()
    {
        $gender = new Gender();
        $name = "Grace";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_FEMALE, $result);
    }

    public function testGetFakeNameFails()
    {
        $gender = new Gender();
        $name = "fslkdjflskdjflsdkfj";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::NAME_NOT_FOUND, $result);

    }

    public function testMostlyMale()
    {
        $gender = new Gender();
        $name = "Sam";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_MOSTLY_MALE, $result);
    }

    public function testMostlyFemale()
    {
        $gender = new Gender();
        $name = "Alexis";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_MOSTLY_FEMALE, $result);
    }

    public function testUnisex()
    {
        $gender = new Gender();
        $name = "Ai-Lin";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_UNISEX_NAME, $result);

        $name = "Aljet";
        $result = $gender->get($name, Gender::USA);
        $this->assertEquals(Gender::IS_UNISEX_NAME, $result);
    }
}
