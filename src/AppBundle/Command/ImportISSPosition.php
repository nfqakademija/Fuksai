<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.13
 * Time: 20.40
 */

namespace AppBundle\Command;


use AppBundle\Entity\ISS;

class ImportISSPosition
{
    public function func()
    {
        $data = $this->getData('https://api.wheretheiss.at/v1/satellites/25544');

        $lat = $data['latitude'];
        $long =$data['longitude'];

        $position = $this->getData('https://api.wheretheiss.at/v1/coordinates/'.$lat.','.$long);

        $iss = new ISS();

        $iss->setLatitude($lat);
        $iss->setLongitude($long);
        $iss->setTimezone($position['timezone_id']);
        $iss->setMapUrl($position['map_url']);



        dump($position);
        exit();
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }
}