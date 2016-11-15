<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Planet;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

/**
 * Class DummyData
 *
 * @package AppBundle\DataFixtures\ORM
 */
class DummyData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__.'/fixtures.yml', $manager);
        Fixtures::load(__DIR__ . '/fixtures_planet_articles.yml', $manager);
    }
}
