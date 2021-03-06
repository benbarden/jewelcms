<?php

class TestArticleLink extends \PHPUnit_Framework_TestCase
{
    private $mockCategory;
    private $mockArticle;

    protected function setUp()
    {
        $this->mockCategory = new \Cms\Entity\Category;
        $this->mockCategory->setName('home');
        $this->mockArticle = new \Cms\Entity\Article;
        $this->mockArticle->setTitle('Test Article');
        $this->mockArticle->setId(1);
        $this->mockArticle->setCreateDate(new \DateTime('2009-12-31 09:45:00'));
    }

    protected function tearDown()
    {
        unset($this->mockCategory);
        unset($this->mockArticle);
    }

    public function testLinkStyle1()
    {
        $expected = '/index.php/article/1/test-article';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\ArticleLink(1, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $iaLink->setArticle($this->mockArticle);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle2()
    {
        $expected = '/article/1/test-article';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\ArticleLink(2, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $iaLink->setArticle($this->mockArticle);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle3()
    {
        $expected = '/test-article';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\ArticleLink(3, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $iaLink->setArticle($this->mockArticle);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle4()
    {
        $expected = '/home/test-article';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\ArticleLink(4, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $iaLink->setArticle($this->mockArticle);
        $this->assertEquals($expected, $iaLink->generate());
    }
    public function testLinkStyle5()
    {
        $expected = '/2009/12/31/test-article';
        $iaOptimiser = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLink = new \Cms\Ia\Link\ArticleLink(5, $iaOptimiser);
        $iaLink->setCategory($this->mockCategory);
        $iaLink->setArticle($this->mockArticle);
        $this->assertEquals($expected, $iaLink->generate());
    }
}