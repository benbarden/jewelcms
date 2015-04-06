<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class Category extends EntityRepository
{
    public function getById($categoryId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Category')
            ->from('Cms\Entity\Category', 'Category')
            ->where($qb->expr()->eq('Category.id', ':id'))
            ->setParameter('id', $categoryId);
        return $qb->getQuery()->getOneOrNullResult();
    }
}