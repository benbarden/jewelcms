<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class Permission extends EntityRepository
{
    private function getPermission($perId = 1, $perName)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Permission.'.$perName)
            ->from('Cms\Entity\Permission', 'Permission')
            ->where($qb->expr()->eq('Permission.id', ':id'))
            ->setParameter('id', $perId);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCreateArticle($perId = 1)
    {
        return $this->getPermission($perId, 'createArticle');
    }

    public function getPublishArticle($perId = 1)
    {
        return $this->getPermission($perId, 'publishArticle');
    }

    public function getEditArticle($perId = 1)
    {
        return $this->getPermission($perId, 'editArticle');
    }

    public function getDeleteArticle($perId = 1)
    {
        return $this->getPermission($perId, 'deleteArticle');
    }

    public function getAttachFile($perId = 1)
    {
        return $this->getPermission($perId, 'attachFile');
    }
}