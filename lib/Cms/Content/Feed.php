<?php


namespace Cms\Content;


use Doctrine\ORM\EntityManager,
    Twig_Environment;

class Feed
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Twig_Environment
     */
    private $themeEngine;

    public function __construct(EntityManager $entityManager, Twig_Environment $engine)
    {
        $this->em = $entityManager;
        $this->themeEngine = $engine;
    }

    public function __destruct()
    {
        unset($this->em);
        unset($this->themeEngine);
    }

    public function generate()
    {
        $recentContent = $this->em->getRepository('Cms\Entity\Article')->getRecentPublic(25);

        $feedBindings = array('FeedItems' => $recentContent);

        $outputHtml = $this->themeEngine->render('feed/rss.twig', $feedBindings);
        file_put_contents(ABS_ROOT.'feed/main.xml', $outputHtml);
    }
}