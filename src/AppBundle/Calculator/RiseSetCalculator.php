<?php

namespace AppBundle\Calculator;

use AppBundle\Entity\PlanetSchedule;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class RiseSetCalculator
 * @package AppBundle\Calculator
 */
class RiseSetCalculator
{
    private $planetMap = array(
        1 => 'Mercury',
        2 => 'Venus',
        3 => '',
        4 => 'Mars',
        5 => 'Jupiter',
        6 => 'Saturn',
        7 => 'Uranus',
        8 => 'Neptune',
    );

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $googleApiKey;

    /**
     * RiseSetCalculator constructor.
     * @param EntityManager $em
     * @param string $googleApiKey
     */
    public function __construct(EntityManager $em, $googleApiKey)
    {
        $this->em = $em;
        $this->googleApiKey = $googleApiKey;
    }

    /**
     * @param $city
     * @return PlanetSchedule[]
     */
    public function getRiseSet($city): array
    {
        $wholeSchedule = array();

        $city = strtolower($city);

        $today = date('Y-m-d');

        for ($i = 1; $i<9; $i++) {
            if ($i == 3) {
                continue;
            }

            $object = $this->planetMap[$i];

            $data = $this->getData('https://maps.googleapis.com/maps/api/geocode/json?address=' . $city);
            $lat = $data['results'][0]['geometry']['location']['lat'];
            $lng = $data['results'][0]['geometry']['location']['lng'];

            $data = $this->getData('https://maps.googleapis.com/maps/api/timezone/json?location=' .
                $lat . ',' .
                $lng . '&timestamp=' .
                time() . '&key=' .
                $this->googleApiKey);
            $timezone = $data['rawOffset'] / 3600;

            $tz_sign = $this->getSign($timezone);
            $timezone = abs($timezone);

            $lat_sign = $this->getSign($lat);
            $lat = abs($lat);

            $lng_sign = $this->getSign($lng);
            $lng = abs($lng);

            $latitude = $this->floatToDeg($lat);
            $longitude = $this->floatToDeg($lng);

            $date_args = explode("-", $today);

            $data = $this->getPlainData('http://aa.usno.navy.mil/cgi-bin/aa_mrst2.pl?form=2&ID=AA' .
                '&year=' . $date_args[0] .
                '&month=' . $date_args[1] .
                '&day=' . $date_args[2] .
                '&reps=1' .
                '&body=' . $i .
                '&place=mercury' .
                '&lon_sign=' . $lng_sign .
                '&lon_deg=' . $longitude['deg'] .
                '&lon_min=' . $longitude['min'] .
                '&lon_sec=1' .
                '&lat_sign=' . $lat_sign .
                '&lat_deg=' . $latitude['deg'] .
                '&lat_min=' . $latitude['min'] .
                '&lat_sec=1' .
                '&height=1' .
                '&tz=' . $timezone .
                '&tz_sign=' . $tz_sign);

            $schedule = $this->parseResponse($data, $i);

            $planetSchedule = new PlanetSchedule();
            $planetSchedule->setObject($object);
            $planetSchedule->setCity($city);
            $planetSchedule->setLongitude($this->degToFloat($longitude));
            $planetSchedule->setLatitude($this->degToFloat($latitude));
            $planetSchedule->setTimezone($timezone);
            $planetSchedule->setDate($today);
            $planetSchedule->setRise($schedule['rise']);
            $planetSchedule->setFall($schedule['fall']);
            $wholeSchedule[] = $planetSchedule;
        }

        return $wholeSchedule;
    }

    /**
     * @param $response
     * @param $planetID
     * @return mixed
     */
    private function parseResponse($response, $planetID)
    {
        $result = array();
        $substring1 = null;
        $substring2 = null;

        switch ($planetID) {
            case 1:
            case 2:
            case 5:
            case 8:
                $substring1 = 1421;
                $substring2 = 1455;
                break;
            case 4:
            case 6:
            case 7:
                $substring1 = 1426;
                $substring2 = 1460;
                break;
        }

        $result['rise'] = substr($response, $substring1, 5);
        $result['fall'] = substr($response, $substring2, 5);

        return $result;
    }

    /**
     * @param $coordinate
     * @return array
     */
    private function floatToDeg($coordinate): array
    {
        $result = array();

        $result['min'] = rtrim(round(($coordinate - floor($coordinate))/5*3, 2)*100, ".0");
        $result['deg'] = rtrim(floor($coordinate), ".0");

        return $result;
    }

    /**
     * @param $coordinate
     * @return string
     */
    private function degToFloat($coordinate): string
    {
        $min = $coordinate['min']/300*5;
        return rtrim($coordinate['deg'], ".0") + round($min, 2);
    }

    /**
     * @param $value
     * @return int
     */
    private function getSign($value): int
    {
        if ($value < 0) {
            return -1;
        }
        return 1;
    }

    /**
     * @param $url
     * @return mixed
     */
    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param $url
     * @return string
     */
    private function getPlainData($url): string
    {
        $data = file_get_contents($url);

        return $data;
    }
}
