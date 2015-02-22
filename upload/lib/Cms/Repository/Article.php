<?php


namespace Cms\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class Article extends EntityRepository
{
    public function countByCategoryPublic($categoryId)
    {
        return $this->countByCategory('public', $categoryId);
    }

    public function countByCategoryAll($categoryId)
    {
        return $this->countByCategory('all', $categoryId);
    }

    private function countByCategory($mode, $categoryId)
    {
        if ($mode == 'public') {
            $statusSql = "AND content_status = 'Published'";
        } else {
            $statusSql = "";
        }

        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Cms\Entity\Category', 'c');
        $rsm->addScalarResult('count', 'count');

        $query = $this->_em->createNativeQuery("
            SELECT count(id) AS count FROM Cms_Content WHERE category_id = ?
            $statusSql
        ", $rsm);
        $query->setParameter(1, $categoryId, Type::INTEGER);
        return $query->getSingleScalarResult();
    }

    public function getByCategoryPublic($categoryId, $limit = 25, $offset = 0, $sortField = "create_date", $sortDirection = "DESC")
    {
        return $this->getContent('public', $categoryId, $limit, $offset, $sortField, $sortDirection);
    }

    public function getByCategoryAll($categoryId, $limit = 25, $offset = 0, $sortField = "create_date", $sortDirection = "DESC")
    {
        return $this->getContent('all', $categoryId, $limit, $offset, $sortField, $sortDirection);
    }

    public function getAll($limit = 25, $offset = 0, $sortField = "create_date", $sortDirection = "DESC")
    {
        return $this->getContent('all', 0, $limit, $offset, $sortField, $sortDirection);
    }

    public function getRecentPublic($limit = 5)
    {
        return $this->getContent('public', 0, $limit);
    }

    private function getSortField($field)
    {
        switch (strtolower($field)) {
            case "author_name":   $dbField = "a.username";     break;
            case "create_date":   $dbField = "a.createDate";   break;
            case "last_updated":  $dbField = "a.lastUpdated";  break;
            case "article_title": $dbField = "a.title";        break;
            case "random":        $dbField = "a.rand()";       break;
            case "custom":        $dbField = "a.articleOrder"; break;
            default:              $dbField = "a.createDate";   break;
        }
        return $dbField;
    }

    private function getSortDir($dir)
    {
        switch (strtolower($dir)) {
            case "asc":  $dbDir = "ASC";  break;
            case "desc": $dbDir = "DESC"; break;
            default:     $dbDir = "DESC"; break;
        }
        return $dbDir;
    }

    private function getContent($mode, $categoryId = 0, $limit = 25, $offset = 0, $sortField = "create_date", $sortDirection = "DESC")
    {
        $mode = strtolower($mode);

        $qb = $this->_em->createQueryBuilder();
        $qb->select('a')->from('Cms\Entity\Article', 'a');
        if ($mode != 'all') {
            $qb->where("a.contentStatus = 'Published'");
        }
        if ($categoryId) {
            $qb->where("a.categoryId = :categoryId");
            $qb->setParameter('categoryId', $categoryId, Type::INTEGER);
        }
        $qb->orderBy($this->getSortField($sortField), $this->getSortDir($sortDirection));
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }
}