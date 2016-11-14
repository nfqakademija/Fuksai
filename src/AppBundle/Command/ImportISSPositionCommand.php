<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.13
 * Time: 20.40
 */

namespace AppBundle\Command;

use AppBundle\Entity\ISS;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportISSPositionCommand
 * @package AppBundle\Command
 */
class ImportISSPositionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import:iss');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getData('https://api.wheretheiss.at/v1/satellites/25544');

        $lat = $data['latitude'];
        $long = $data['longitude'];

        $position = $this->getData(
            'https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $lat . ',' . $long.'&key=AIzaSyBQBUCFjc27X6txm90las2YFe_bNeDDBRw');

        $iss = new ISS();

        $iss->setLatitude($lat);
        $iss->setLongitude($long);
        $iss->setCountry($position['results']['adress_components'][3]['long_name']);
        $iss->setMapUrl('https://www.google.com/maps/@'. $lat . ',' .$long. ',10z');

        $this->save($iss);
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    private function save(ISS $iss)
    {
        $em = $this->getEntityManager();
        $em->persist($iss);
        $em->flush();
    }

    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
