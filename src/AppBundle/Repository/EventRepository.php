<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository
 * @package AppBundle\Repository
 */
class EventRepository extends EntityRepository
{
    public function findEvent($date): array
    {
        return $this->createQueryBuilder('event')
            ->where('event.date =:date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function findNextEvent(): array
    {
        $Date = new \DateTime();
        $currentDate = $Date->format('Y-m-d');
        return $this->createQueryBuilder('event')
            ->setParameter('date', $currentDate)
            ->where('event.date >:date')
            ->orderBy('event.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
