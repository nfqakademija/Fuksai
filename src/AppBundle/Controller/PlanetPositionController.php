<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.2
 * Time: 06.54
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlanetPositionController extends Controller
{
    /**
     * @Route("/riseset", name="rise_set")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRiseSet()
    {
        $em = $this->getDoctrine()->getManager();
        $schedule = $em->getRepository('AppBundle:PlanetSchedule')->findAll();

        return $this->render('services/rise_set.html.twig', [
            'planets' => $schedule,
            'page' => 'Night Sky',
        ]);
    }
}
