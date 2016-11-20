<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.20
 * Time: 18.31
 */

namespace AppBundle\Controller;


class RiseSetCalculator
{
    private $city;
    private $latitude;
    private $longitude;
    private $timezone;

    public function __construct($city, $latitude, $longitude, $timezone)
    {
        $this->city=$city;
        $this->latitude=$latitude;
        $this->longitude=$longitude;
        $this->timezone=$timezone;
    }

    public function getRiseSet($object)
    {

    }

    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }

    private function getPlainData($url)
    {
        $data = file_get_contents($url);

        return $data;
    }
}
