<?php


namespace Cms\Content;


use Doctrine\ORM\EntityManager,
    Twig_Environment;

class Sitemap
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Twig_Environment
     */
    private $themeEngine;

    /**
     * @var \Cms\Repository\Category
     */
    private $repoCategory;

    /**
     * @var \Cms\Repository\Article
     */
    private $repoArticle;

    /**
     * @var array
     */
    private $sitemapList;

    public function __construct(EntityManager $entityManager, Twig_Environment $engine)
    {
        $this->em = $entityManager;
        $this->themeEngine = $engine;

        $this->repoCategory = $this->em->getRepository('Cms\Entity\Category');
        $this->repoArticle = $this->em->getRepository('Cms\Entity\Article');

        $this->sitemapList = array();
    }

    public function __destruct()
    {
        unset($this->em);
        unset($this->themeEngine);
        unset($this->sitemapList);
    }

    private function getSitemapUrl($categoryId)
    {
        if ($categoryId == null) {
            $url = 'sitemap/category-none.xml';
        } else {
            $url = 'sitemap/category-'.$categoryId.'.xml';
        }

        return $url;
    }

    public function generateSitemapIndex()
    {
        $categoryStats = $this->repoCategory->getStatsByCategory();

        $categoryIdList = array();

        foreach ($categoryStats as $catStat) {

            $catId = $catStat['category_id'];
            $this->sitemapList[] = array('Url' => $this->getSitemapUrl($catId), 'CatId' => $catId);
            if ($catId != null) {
                $categoryIdList[] = $catId;
            }

        }

        $sitemapIndexHtml = $this->themeEngine->render('sitemap/sitemap-index.twig', array('SitemapList' => $this->sitemapList));
        file_put_contents(ABS_ROOT.'sitemap/index.xml', $sitemapIndexHtml);
    }

    public function generateCategorySitemap($categoryId)
    {
        $sitemapContent = $this->repoArticle->getByCategoryPublic($categoryId, 0);
        $sitemapHtml = $this->themeEngine->render('sitemap/sitemap.twig', array('ItemList' => $sitemapContent));
        $url = $this->getSitemapUrl($categoryId);
        file_put_contents(ABS_ROOT.$url, $sitemapHtml);
    }

    public function generateAll()
    {
        $this->generateSitemapIndex();

        foreach ($this->sitemapList as $sitemap) {

            // skip Sitemap Index
            if (!array_key_exists('CatId', $sitemap)) continue;

            $this->generateCategorySitemap($sitemap['CatId']);

        }

    }
}