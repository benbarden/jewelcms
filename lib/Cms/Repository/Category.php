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

    public function getTopLevel()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Category')
            ->from('Cms\Entity\Category', 'Category')
            ->where('Category.parentId IS NULL');
        return $qb->getQuery()->getResult();
    }

    public function getStatsByCategory()
    {
        $conn = $this->_em->getConnection();
        $result = $conn->fetchAll('
            SELECT a.category_id, count(*) AS count
            FROM Cms_Content a
            LEFT JOIN Cms_Categories c ON a.category_id = c.id
            WHERE a.content_status = "Published"
            GROUP BY a.category_id
            ORDER BY a.category_id
        ');
        return $result;
    }
}