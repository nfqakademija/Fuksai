<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository
 * @package AppBundle\Repository
 */
class EventRepository extends EntityRepository
{
    public function findEvent($date)
    {
        return $this->createQueryBuilder('event')
            ->where('event.date =:date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
