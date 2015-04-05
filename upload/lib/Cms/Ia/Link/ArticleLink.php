<?php


namespace Cms\Ia\Link;

use Cms\Entity\Category,
    Cms\Entity\Article;


class ArticleLink extends Base
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @var Category
     */
    private $category;

    public function __destruct()
    {
        unset($this->article);
        unset($this->category);
        parent::__destruct();
    }

    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    private function getOptimisedArticleUrl()
    {
        return $this->optimiser->optimise($this->article->getTitle());
    }

    private function getOptimisedCategoryUrl()
    {
        if ($this->category) {
            $categoryName = $this->category->getName();
        } else {
            $categoryName = 'no-category';
        }
        return $this->optimiser->optimise($categoryName);
    }

    /**
     * view.php/article/1/hello-world
     * @return string
     */
    protected function generateLinkStyleClassic()
    {
        return URL_ROOT.sprintf('index.php/article/%s/%s',
            $this->article->getId(), $this->getOptimisedArticleUrl());
    }

    /**
     * article/1/hello-world
     * @return string
     */
    protected function generateLinkStyleLong()
    {
        return URL_ROOT.sprintf('article/%s/%s',
            $this->article->getId(), $this->getOptimisedArticleUrl());
    }

    /**
     * hello-world
     * @return string
     */
    protected function generateLinkStyleTitleOnly()
    {
        return URL_ROOT.$this->getOptimisedArticleUrl();
    }

    /**
     * category-name/hello-world
     * @return string
     */
    protected function generateLinkStyleCategoryAndTitle()
    {
        return URL_ROOT.sprintf('%s/%s',
            $this->getOptimisedCategoryUrl(), $this->getOptimisedArticleUrl());
    }

    /**
     * 2009/12/31/hello-world
     * @return string
     */
    protected function generateLinkStyleDateAndTime()
    {
        $articleDate = $this->article->getCreateDate();
        $articleYY = $articleDate->format('Y');
        $articleMM = $articleDate->format('m');
        $articleDD = $articleDate->format('d');
        return URL_ROOT.sprintf('%s/%s/%s/%s',
            $articleYY, $articleMM, $articleDD, $this->getOptimisedArticleUrl());
    }
}