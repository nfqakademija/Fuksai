<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
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
}
