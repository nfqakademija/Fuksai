<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();

        return $this->render('default/index.html.twig', [
            'planets' => $planets
        ]);
    }

    /**
     * @Route("/list")
     */
    public function planetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();

        return $this->render('list.html.twig', [
            'planets' => $planets
        ]);
    }

    /**
     * @Route("/planets/{planetName}", name="show_planet")
     */
    public function showPlanet($planetName)
    {
        $em = $this->getDoctrine()->getManager();
        $planet = $em->getRepository('AppBundle:Planet')->findOneBy(['name' => $planetName]);
        if(!$planet){
            throw $this->createNotFoundException('Ups! No planet found!');
        }
        return $this->render('planet.html.twig', [
            'planet' => $planet
        ]);
    }

}
