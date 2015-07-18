<?php


namespace Cms\Helper\EntityValue;


class Base
{
    /**
     * @var string
     */
    protected $repoName;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repo;

    public function __construct($entityManager)
    {
        if (!$this->repoName) {
            throw new \Exception('Repo name must be set in child class');
        }
        $this->entityManager = $entityManager;
        $this->setupRepo();
    }

    public function __destruct()
    {
        unset($this->repo);
        unset($this->entityManager);
    }

    protected function setupRepo()
    {
        $this->repo = $this->entityManager->getRepository($this->repoName);
    }
}