<?php

namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Repository\AsteroidRepository;

/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.12
 * Time: 14.32
 */
class AsteroidsImportCommand
{
//    protected function configure()
//    {
//        $this
//            ->setName('app:import:asteroids')
//            ->setDefinition('Import incoming asteroids')
//            ->setHelp('This command imports asteroid list');
//    }

//    protected function execute(InputInterface $input, OutputInterface $output)
//    {
//        $output->writeln('Starting to import asteroid list');
//
//        $date = '2016-11-12';
//        $data = $this->getData('https://api.nasa.gov/neo/rest/v1/feed?start_date='.$date.'&end_date=2016-11-13&detailed=false&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
//        $count = $data['element_count'];
//        for ($i = 0; $i < $count; $i++)
//        {
//            $asteroid = new \AppBundle\Entity\Asteroid();
//            $asteroid->setName($data['near_earth_objects'][$date][1]['name']);
//            $asteroid->setDiameter($data['near_earth_objects'][$date][1]['estimated_diameter']['meters']['estimated_diameter_max']);
//            $asteroid->setVelocity($data['near_earth_objects'][$date][1]['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']);
//            $asteroid->setMissDistance($data['near_earth_objects'][$date][1]['close_approach_data'][0]['miss_distance']['kilometers']);
//            dump($asteroid);
//            exit;
//        }
//    }

//    public function getList()
//    {
//        $date = '2016-11-12';
//        $data = $this->getData('https://api.nasa.gov/neo/rest/v1/feed?start_date='.$date.'&end_date=2016-11-13&detailed=false&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
//        $count = $data['element_count'];
//        for ($i = 0; $i < $count; $i++)
//        {
//            $asteroid = new \AppBundle\Entity\Asteroid();
//            $asteroid->setName($data['near_earth_objects'][$date][1]['name']);
//            $asteroid->setDiameter($data['near_earth_objects'][$date][1]['estimated_diameter']['meters']['estimated_diameter_max']);
//            $asteroid->setVelocity($data['near_earth_objects'][$date][1]['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']);
//            $asteroid->setMissDistance($data['near_earth_objects'][$date][1]['close_approach_data'][0]['miss_distance']['kilometers']);
//        }
//    }

    public function func()
    {
//        $date = '2016-11-13';
//        $data = $this
//            ->getData('https://api.nasa.gov/neo/rest/v1/feed?start_date=2016-11-13&end_date=2016-11-13&detailed=false&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
//
//        $count = $data['element_count'];
//
//
//        for ($i = 0; $i < $count; $i++)
//        {
//            $asteroid = new \AppBundle\Entity\Asteroid();
//            $asteroid->setName($data['near_earth_objects'][$date][1]['name']);
//            $asteroid->setDiameter($data['near_earth_objects'][$date][2]['estimated_diameter']['meters']['estimated_diameter_max']);
//            $asteroid->setVelocity($data['near_earth_objects'][$date][3]['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']);
//            $asteroid->setMissDistance($data['near_earth_objects'][$date][4 ]['close_approach_data'][0]['miss_distance']['kilometers']);
//
//
//        }

        $data = $this->getData('http://api.predictthesky.org/events/all');
        dump($data);
        exit();
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }
}