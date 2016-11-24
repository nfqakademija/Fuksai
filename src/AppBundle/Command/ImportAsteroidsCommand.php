<?php

namespace AppBundle\Command;

use AppBundle\Entity\Asteroid;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Repository\AsteroidRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.12
 * Time: 14.32
 */

/*
 * Class ImportAsteroidsCommand
 * @package AppBundle\Command
 */
class ImportAsteroidsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:asteroids')
            ->setDescription('Import incoming asteroids');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting to import asteroid list');

        $api_key = $this->getContainer()->getParameter('nasa_api_key');

        $date = date('Y-m-d');
        $data = $this->getData('https://api.nasa.gov/neo/rest/v1/feed?start_date='
            .$date.'&end_date='.$date.'&detailed=false&api_key=' . $api_key);

        $count = $data['element_count'];

        $em = $this->getEntityManager();

        for ($i = 0; $i < $count; $i++) {
            $asteroid = new \AppBundle\Entity\Asteroid();
            $asteroid
                ->setName($data['near_earth_objects'][$date][$i]['name'])
                ->setDiameter($data['near_earth_objects'][$date]
                [$i]['estimated_diameter']['meters']['estimated_diameter_max'])
                ->setVelocity($data['near_earth_objects'][$date]
                [$i]['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'])
                ->setMissDistance($data['near_earth_objects'][$date]
                [$i]['close_approach_data'][0]['miss_distance']['kilometers']);

            $this->save($em, $asteroid);
        }

        $em->flush();

        $output->writeln('Import successful!');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param EntityManager $manager
     * @param Asteroid $asteroid
     */
    private function save(EntityManager $manager, Asteroid $asteroid)
    {
        $manager->persist($asteroid);
    }

    /**
     * @return mixed
     */
    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
