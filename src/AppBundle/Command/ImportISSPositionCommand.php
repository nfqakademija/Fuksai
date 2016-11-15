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
            ->setName('app:import:iss')
            ->setDescription('Imports and calculates ISS position');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting to import International Space Station position');

        $data = $this->getData('https://api.wheretheiss.at/v1/satellites/25544');

        $lat = $data['latitude'];
        $long = $data['longitude'];

        $position = $this->getData(
            'https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $lat . ',' . $long.'&key=AIzaSyBQBUCFjc27X6txm90las2YFe_bNeDDBRw');

        $em = $this->getEntityManager();

        $iss = $em->getRepository('AppBundle:ISS')->find(1);

        $iss->setLatitude($lat);
        $iss->setLongitude($long);
        $iss->setMapUrl('https://www.google.com/maps/@'. $lat . ',' .$long. ',10z');

        if(isset($position['results'][0]))
        {
            $iss->setCountry($position['results'][0]['address_components'][0]['long_name']);

        }
        else
        {
            $iss->setCountry('No country');
        }
        $em->flush();

        $output->writeln('Import successful!');
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    private function clearTable()
    {

    }
}
