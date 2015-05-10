<?php


namespace Cms\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;


class Setting extends EntityRepository
{
    public function getByName($name)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Setting')
            ->from('Cms\Entity\Setting', 'Setting')
            ->where($qb->expr()->eq('Setting.preference', ':preference'))
            ->setParameter('preference', $name);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCMSVersion()
    {
        return $this->getByName(\Cms\Entity\Setting::SETTING_CMS_VERSION);
    }
}