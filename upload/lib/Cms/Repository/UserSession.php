<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class UserSession extends EntityRepository
{
    public function getFromCookie($sessionId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('UserSession')
            ->from('Cms\Entity\UserSession', 'UserSession')
            ->where($qb->expr()->eq('UserSession.sessionId', ':sessionId'))
            ->setParameter('sessionId', $sessionId);
        return $qb->getQuery()->getOneOrNullResult();
    }
}