<?php

class TestFactory extends \PHPUnit_Framework_TestCase
{
    public function testDummy()
    {
        $expected = true;
        $actual = false;
        $this->assertEquals($expected, $actual);
    }
}