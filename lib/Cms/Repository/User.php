<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class User extends EntityRepository
{
    public function getById($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('User')
            ->from('Cms\Entity\User', 'User')
            ->where($qb->expr()->eq('User.id', ':id'))
            ->setParameter('id', $userId);
        return $qb->getQuery()->getOneOrNullResult();
    }
}