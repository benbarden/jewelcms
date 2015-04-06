<?php


namespace Cms\Ia\Link;

use Cms\Entity\Category,
    Cms\Entity\Article;


class CategoryLink extends Base
{
    /**
     * @var Category
     */
    private $category;

    public function __destruct()
    {
        unset($this->category);
        parent::__destruct();
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    private function getOptimisedCategoryUrl()
    {
        return $this->optimiser->optimise($this->category->getName());
    }

    /**
     * view.php/category/1/home/
     * @return string
     */
    protected function generateLinkStyleClassic()
    {
        return URL_ROOT.sprintf('index.php/category/%s/%s/',
            $this->category->getCategoryId(), $this->getOptimisedCategoryUrl());
    }

    /**
     * area/1/home/
     * @return string
     */
    protected function generateLinkStyleLong()
    {
        return URL_ROOT.sprintf('category/%s/%s/',
            $this->category->getCategoryId(), $this->getOptimisedCategoryUrl());
    }

    /**
     * area-name/
     * @return string
     */
    protected function generateLinkStyleTitleOnly()
    {
        return URL_ROOT.$this->getOptimisedCategoryUrl().'/';
    }

    /**
     * category-name/
     * NOTE: as there is no article in the link, this is the same as Title Only
     * @return string
     */
    protected function generateLinkStyleCategoryAndTitle()
    {
        return URL_ROOT.$this->getOptimisedCategoryUrl().'/';
    }

    /**
     * category-name/
     * NOTE: as there is no article in the link, this is the same as Title Only
     * @return string
     */
    protected function generateLinkStyleDateAndTime()
    {
        return URL_ROOT.$this->getOptimisedCategoryUrl().'/';
    }
}