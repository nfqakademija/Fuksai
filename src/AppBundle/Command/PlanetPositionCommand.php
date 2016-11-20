<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.18
 * Time: 20.42
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlanetPositionCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:planet-position')
            ->setDescription('Gets planet rise and set times');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $altitude = -0.833;
        //$local_sidereal_time;
        //$greenwich_sidereal_time;

//        $url = "http://aa.usno.navy.mil/cgi-bin/aa_mrst2.pl?form=2&ID=AA&year=2016&month=11&day=18&reps=5&body=2&place=aaa&lon_sign=-1&lon_deg=50&lon_min=50&lon_sec=50&lat_sign=1&lat_deg=50&lat_min=50&lat_sec=50&height=2&tz=3&tz_sign=1";
//        $ch = curl_init();
//        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//        $page = curl_exec($ch);
//        curl_close($ch);


        $data = file_get_contents('http://aa.usno.navy.mil/cgi-bin/aa_mrst2.pl?form=2&ID=AA&year=2016&month=11&day=18&reps=5&body=2&place=aaa&lon_sign=-1&lon_deg=50&lon_min=50&lon_sec=50&lat_sign=1&lat_deg=50&lat_min=50&lat_sec=50&height=2&tz=3&tz_sign=1');
        var_dump($data);
        exit;
    }

    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }
}
