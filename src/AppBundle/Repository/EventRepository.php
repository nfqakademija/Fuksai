<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/28/16
 * Time: 6:47 PM
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

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

    public function findNextEvent()
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
