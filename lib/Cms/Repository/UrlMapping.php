<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class UrlMapping extends EntityRepository
{
    /**
     * @param $url
     * @return \Cms\Entity\UrlMapping
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByUrl($url)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('UrlMapping')
            ->from('Cms\Entity\UrlMapping', 'UrlMapping')
            ->where($qb->expr()->eq('UrlMapping.relativeUrl', ':relativeUrl'))
            ->setParameter('relativeUrl', $url);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function activateById($rowId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->update('Cms\Entity\UrlMapping', 'um')
            ->set('um.isActive', $qb->expr()->literal('Y'))
            ->where($qb->expr()->eq('um.id', ':rowId'))
            ->setParameters(array('rowId' => $rowId));
        $qb->getQuery()->execute();
    }

    public function deactivateByArticle($articleId, $excludeRowId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->update('Cms\Entity\UrlMapping', 'um')
            ->set('um.isActive', $qb->expr()->literal('N'))
            ->where($qb->expr()->eq('um.articleId', ':articleId'))
            ->andWhere(($qb->expr()->neq('um.id', ':excludeRowId')))
            ->setParameters(array('articleId' => $articleId, 'excludeRowId' => $excludeRowId));
        $qb->getQuery()->execute();
    }

    public function deactivateByCategory($categoryId, $excludeRowId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->update('Cms\Entity\UrlMapping', 'um')
            ->set('um.isActive', $qb->expr()->literal('N'))
            ->where($qb->expr()->eq('um.categoryId', ':categoryId'))
            ->andWhere(($qb->expr()->neq('um.id', ':excludeRowId')))
            ->setParameters(array('categoryId' => $categoryId, 'excludeRowId' => $excludeRowId));
        $qb->getQuery()->execute();
    }

    public function deleteAllByArticle($articleId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->delete('Cms\Entity\UrlMapping', 'um')
            ->where($qb->expr()->eq('um.articleId', ':articleId'))
            ->setParameters(array('articleId' => $articleId));
        $qb->getQuery()->execute();
    }

    public function deleteAllByCategory($categoryId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->delete('Cms\Entity\UrlMapping', 'um')
            ->where($qb->expr()->eq('um.categoryId', ':categoryId'))
            ->setParameters(array('categoryId' => $categoryId));
        $qb->getQuery()->execute();
    }

}