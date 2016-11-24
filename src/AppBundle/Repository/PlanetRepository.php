<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PlanetRepository
 * @package AppBundle\Repository
 */
class PlanetRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findPlanets()
    {
        return $this->createQueryBuilder('planet')
            ->select('planet.keyName')
            ->getQuery()
            ->execute();
    }
}
