<?php


namespace Cms\Theme\User;

use Cms\Entity\Article as EntityArticle,
    Cms\Entity\Category as EntityCategory,
    Cms\Core\Di\Container;


class Article
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \Cms\Entity\Article
     */
    private $article;

    /**
     * @var \Cms\Entity\Category
     */
    private $category;

    /**
     * @var string
     */
    private $themeFile;

    /**
     * @var array
     */
    private $bindings;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->themeFile = 'core/article.twig';
    }

    public function __destruct()
    {
        unset($this->container);
        unset($this->article);
        unset($this->category);
    }

    public function setArticle(EntityArticle $article)
    {
        $this->article = $article;
    }

    public function setCategory(EntityCategory $category)
    {
        $this->category = $category;
    }

    public function setupBindings()
    {
        $articleId = $this->article->getId();
        $articleTitle = stripslashes($this->article->getTitle());
        $articlePermalink = $this->article->getPermalink();

        $bindings = array();

        $bindings['Page']['Type'] = 'article';
        $bindings['Page']['Title'] = $articleTitle;

        // Wrapper IDs and classes
        $bindings['Page']['WrapperId'] = sprintf('article-page-%s', $articleId);
        $bindings['Page']['WrapperClass'] = 'article-page';

        $bindings['Page']['CanonicalUrl'] = $articlePermalink;

        // Date
        $dateFormat = $this->container->getSetting('DateFormat');
        $iaLink = $this->container->getServiceLocator()->getIALinkArticle();

        // Current page
        $contentArticle = new \Cms\Content\Article($this->article, $iaLink, $this->category);
        $bindings['Article']['Id'] = $articleId;
        $bindings['Article']['Title'] = $articleTitle;
        $bindings['Article']['Permalink'] = $articlePermalink;
        $bindings['Article']['Body'] = $contentArticle->getFullBody();
        $bindings['Article']['Date'] = $this->article->getCreateDate()->format($dateFormat);

        $articleAuthorId = $this->article->getAuthorId();
        $articleAuthor = $this->container->getDoctrineRepository('Cms\Entity\User')->getById($articleAuthorId);
        /* @var \Cms\Entity\User $articleAuthor */
        $bindings['Article']['Author']['Id'] = $articleAuthor->getId();
        $bindings['Article']['Author']['Username'] = $articleAuthor->getDisplayName();

        $this->bindings = $bindings;
    }

    public function getFile()
    {
        return $this->themeFile;
    }

    public function getBindings()
    {
        return $this->bindings;
    }
} 