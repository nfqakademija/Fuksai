<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PlanetsController
 * @package AppBundle\Controller
 */
class PlanetsController extends Controller
{
    /**
     * @Route("/planets/{planetName}", name="show_planet")
     * @param $planetName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planetAction($planetName)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em
            ->getRepository('AppBundle:Planet')
            ->findAll();
        $planet = $em
            ->getRepository('AppBundle:Planet')
            ->findOneBy(['name' => $planetName]);
        $video = $em
            ->getRepository('AppBundle:Video')
            ->findOneBy(['keyName' => $planetName]);
        if (!$planet) {
            throw $this->createNotFoundException('Ups! No planet found!');
        }
        return $this->render('planet/planet.html.twig', [
            'planet' => $planet,
            'planetsList' => $planets,
            'video' => $video
        ]);
    }
}
