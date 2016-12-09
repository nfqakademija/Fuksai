<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.20
 * Time: 18.31
 */

namespace AppBundle\Calculator;

use AppBundle\Entity\PlanetSchedule;
use AppBundle\ExceptionLib\NoApiResponseException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Debug\Exception\ContextErrorException;

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
     * @var PlanetSchedule[]
     */
    private $scheduleList;
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
    public function getRiseSet($city)
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

            $tz_sign = Converter::getSign($timezone);
            $timezone = abs($timezone);

            $lat_sign = Converter::getSign($lat);
            $lat = abs($lat);

            $lng_sign = Converter::getSign($lng);
            $lng = abs($lng);

            $latitude = Converter::floatToDeg($lat);
            $longitude = Converter::floatToDeg($lng);

            $date_args = explode("-", $today);

            $data = $this->getPlainData('http://aa.usno.navy.mil/cgi-bin/aa_mrst2.pl?form=2&ID=AA' .
                '&year=' . $date_args[0] .
                '&month=' . $date_args[1] .
                '&day=' . $date_args[2] .
                '&reps=1' .
                '&body=' . $i .
                '&place=mercury' .
                '&lon_sign=' . $lng_sign .
                '&lon_deg=' . $longitude->getDegrees() .
                '&lon_min=' . $longitude->getMinutes() .
                '&lon_sec=1' .
                '&lat_sign=' . $lat_sign .
                '&lat_deg=' . $latitude->getDegrees() .
                '&lat_min=' . $latitude->getMinutes() .
                '&lat_sec=1' .
                '&height=1' .
                '&tz=' . $timezone .
                '&tz_sign=' . $tz_sign);

            $schedule = $this->parseResponse($data, $i);

            $planetSchedule = new PlanetSchedule();
            $planetSchedule->setObject($object);
            $planetSchedule->setCity($city);
            $planetSchedule->setLongitude(Converter::degToFloat($longitude));
            $planetSchedule->setLatitude(Converter::degToFloat($latitude));
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
     * @param $url
     * @return mixed
     */
    private function getData($url)
    {
        try {
            $json = file_get_contents($url);
            $data = json_decode($json, true);
        } catch (ContextErrorException $e) {
            throw new NoApiResponseException('No response from the google');
        }
        return $data;
    }

    /**
     * @param $url
     * @return string
     */
    private function getPlainData($url)
    {
        try {
            $data = file_get_contents($url);
        } catch (ContextErrorException $e) {
            throw new NoApiResponseException('No response from the api call');
        }
        return $data;
    }
}
