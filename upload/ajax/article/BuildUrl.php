<?php

require '../../sys/header.php';

use \Cms\Exception\Ajax\AjaxException,
    \Cms\Entity\Article,
    \Cms\Core\Di\Container;

class GetUrl
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $currentUrl;

    public function __construct($container)
    {
        $this->container = $container;

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        $title = isset($_POST['title']) ? $_POST['title'] : "";
        if (!$title) {
            throw new AjaxException('Title is not set');
        }

        $currentUrl = isset($_POST['currentUrl']) ? $_POST['currentUrl'] : "";

        $this->id = $id;
        $this->title = $title;
        $this->currentUrl = $currentUrl;
    }

    public function __destruct()
    {
        unset($this->container);
    }

    public function render()
    {
        $dataArray = array();
        $dataArray['error'] = '';
        $dataArray['title'] = $this->title;

        if ($this->id) {
            $useDataModel = true;
        } else {
            $useDataModel = false;
        }

        $em = $this->container->getServiceLocator()->getCmsEntityManager();

        if ($useDataModel) {
            // Load the article
            $modelArticle = $em->getRepository('Cms\Entity\Article')->getById($this->id);
            if (!$modelArticle) {
                throw new AjaxException(sprintf('Article not found: %s', $this->id));
            }
        } else {
            // Create a draft. We need this for the id in the URL.
            $authorId = $this->container->getServiceLocator()->getAuthCurrentUser()->getId();
            $modelArticle = new Article;
            $modelArticle->setTitle($this->title);
            $modelArticle->setAuthorId($authorId);
            $modelArticle->setCreateDate(new \DateTime("now"));
            $modelArticle->setLastUpdated(new \DateTime("now"));
            $modelArticle->setStatusAutosave();
            $em->persist($modelArticle);
            $em->flush();
        }

        $iaLinkArticle = $this->container->getServiceLocator()->getIALinkArticle();
        $iaLinkArticle->setArticle($modelArticle);
        $articleUrl = $iaLinkArticle->generate();
        $dataArray['url'] = $articleUrl;
        $dataArray['id'] = $modelArticle->getId();
        print(json_encode($dataArray));
    }
}

try {
    $getUrl = new GetUrl($cmsContainer);
    $getUrl->render();
} catch (AjaxException $e) {
    $errorArray = array('error' => $e->getMessage());
    print(json_encode($errorArray));
}
exit;
