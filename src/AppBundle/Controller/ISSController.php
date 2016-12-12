<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ISSController extends Controller
{
    /**
     * @Route("/space_station", name="space_station")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showISS()
    {
        $em = $this->getDoctrine()->getManager();
        $coordinates = $em->getRepository('AppBundle:ISS')->find(1);
        $apiKey = $this->getParameter('google_timezone_api_key');
        $page = 'iss';
        $filters = [];

        return $this->render('services/space_station.html.twig', [
            'apiCall' => 'https://maps.googleapis.com/maps/api/js?key='.$apiKey.'&callback=initMap',
            'lat' => $coordinates->getLatitude(),
            'long' => $coordinates->getLongitude(),
            'country' => $coordinates->getCountry(),
            'page' => $page,
            'filters' => $filters
        ]);
    }
}
