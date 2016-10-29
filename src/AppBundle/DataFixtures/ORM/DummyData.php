<?php
namespace AppBundle\DataFixtures\ORM;
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 10/29/16
 * Time: 2:26 PM
 */

use AppBundle\Entity\Planet;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class DummyData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__.'/fixtures.yml', $manager);
    }
}