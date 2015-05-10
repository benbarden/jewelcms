<?php

require '../../../lib/Cms/Legacy/Header.php';

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
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Cms\Entity\User
     */
    private $authCurrentUser;

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

    /**
     * @var integer
     */
    private $categoryId;

    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->getServiceLocator()->getCmsEntityManager();

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        $title = isset($_POST['title']) ? $_POST['title'] : "";
        if (!$title) {
            throw new AjaxException('Title is not set');
        }

        $currentUrl = isset($_POST['currentUrl']) ? $_POST['currentUrl'] : "";

        $categoryId = isset($_POST['categoryId']) ? (int) $_POST['categoryId'] : 0;

        $this->id = $id;
        $this->title = $title;
        $this->currentUrl = $currentUrl;
        $this->categoryId = $categoryId;

        $this->validateAccess();
    }

    public function __destruct()
    {
        unset($this->container);
        unset($this->em);
        unset($this->authCurrentUser);
    }

    public function validateAccess()
    {
        $authCurrentUser = $this->container->getServiceLocator()->getAuthCurrentUser();
        if (!$authCurrentUser) {
            throw new AjaxException('Access denied');
        }

        $repoPermission = $this->em->getRepository('Cms\Entity\Permission');
        $accessPermission = new \Cms\Access\Permission($repoPermission, $authCurrentUser);
        if (!$accessPermission->canCreateArticle()) {
            throw new AjaxException('Access denied');
        }

        $this->authCurrentUser = $authCurrentUser;
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

        if ($useDataModel) {
            // Load the article
            $modelArticle = $this->em->getRepository('Cms\Entity\Article')->getById($this->id);
            if (!$modelArticle) {
                throw new AjaxException(sprintf('Article not found: %s', $this->id));
            }
            // Allow existing articles to have a URL regenerated
            if (!$this->currentUrl) {
                $modelArticle->setTitle($this->title);
            }
        } else {
            // Create a draft. We need this for the id in the URL.
            $authorId = $this->authCurrentUser->getId();
            $modelArticle = new Article;
            $modelArticle->setTitle($this->title);
            //$modelArticle->setAuthorId($authorId);
            $modelArticle->setAuthor($this->authCurrentUser);
            $modelArticle->setCreateDate(new \DateTime("now"));
            $modelArticle->setLastUpdated(new \DateTime("now"));
            $modelArticle->setStatusAutosave();
            $this->em->persist($modelArticle);
            $this->em->flush();
        }

        if ($this->categoryId) {
            $modelCategory = $this->em->getRepository('Cms\Entity\Category')->getById($this->categoryId);
        } else {
            $modelCategory = null;
        }

        $iaLinkArticle = $this->container->getServiceLocator()->getIALinkArticle();
        $iaLinkArticle->setArticle($modelArticle);
        if ($modelCategory) {
            $iaLinkArticle->setCategory($modelCategory);
        }
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
