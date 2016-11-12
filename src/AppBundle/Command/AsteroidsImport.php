<?php

namespace AppBundle\Command;

/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.12
 * Time: 14.32
 */
class NewsImport
{
    public function getApproachingAsteroids()
    {
        $date = '2016-11-12';
        $data = $this->getData('https://api.nasa.gov/neo/rest/v1/feed?start_date='.$date.'&end_date=2016-11-13&detailed=false&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
        $count = $data['element_count'];
        for ($i = 0; $i < $count; $i++)
        {
            $asteroid = new \AppBundle\Entity\Asteroid();
            $asteroid->setName($data['near_earth_objects'][$date][$i]['name']);
            $asteroid->setDiameter($data['near_earth_objects'][$date][$i]['estimated_diameter']['meters']['estimated_diameter_max']);
            $asteroid->setVelocity($data['near_earth_objects'][$date][$i]['close_approach_data']['relative_velocity']['kilometers_per_hour']);
            $asteroid->setMissDistance($data['near_earth_objects'][$date][$i]['close_approach_data']['miss_distance']['kilometers']);
            var_dump($asteroid);
        }
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }
}