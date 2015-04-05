<?php

class TestCategoryLink extends \PHPUnit_Framework_TestCase
{
    private $mockCategory;

    protected function setUp()
    {
        $this->mockCategory = new \Cms\Entity\Category;
    }

    protected function tearDown()
    {
        unset($this->mockCategory);
    }

    public function testLinkStyle1()
    {
        $expected = '/index.php/category/1/home/';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\CategoryLink(1, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle2()
    {
        $expected = '/category/1/home/';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\CategoryLink(2, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle3()
    {
        $expected = '/home/';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\CategoryLink(3, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle4()
    {
        $expected = '/home/';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\CategoryLink(4, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle5()
    {
        $expected = '/home/';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\CategoryLink(5, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $this->assertEquals($expected, $iaLink->generate());
    }
}