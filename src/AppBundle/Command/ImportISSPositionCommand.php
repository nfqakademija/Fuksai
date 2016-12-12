<?php

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
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:iss')
            ->setDescription('Imports and calculates ISS position');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting to import International Space Station position');

        $api_key = $this->getContainer()->getParameter('googlemaps_api_key');

        $data = $this->getData('https://api.wheretheiss.at/v1/satellites/25544');
        $lat = $data['latitude'];
        $long = $data['longitude'];

        $position = $this->getData(
            'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key='.$api_key
        );

        $em = $this->getEntityManager();
        if (!empty($em->getRepository('AppBundle:ISS')->find(1))) {
            $iss = $em->getRepository('AppBundle:ISS')->find(1);

            $iss->setLatitude($lat);
            $iss->setLongitude($long);
            $iss->setMapUrl('https://www.google.com/maps/@' . $lat . ',' . $long . ',10z');

            if (isset($position['results'][0])) {
                $iss->setCountry($position['results'][0]['address_components'][0]['long_name']);
            } else {
                $iss->setCountry('No country');
            }
        } else {
            $iss = new ISS();
            $iss->setLatitude($lat);
            $iss->setLongitude($long);
            $iss->setMapUrl('https://www.google.com/maps/@' . $lat . ',' . $long . ',10z');

            if (isset($position['results'][0])) {
                $iss->setCountry($position['results'][0]['address_components'][0]['long_name']);
            } else {
                $iss->setCountry('No country');
            }
            $em->persist($iss);
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
