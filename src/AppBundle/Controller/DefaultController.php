<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $news = $em->getRepository('AppBundle:Article')->findAll();
        $event = $em->getRepository('AppBundle:Event')->findNextEvent();
        if (!isset($event[0])) {
            $event = null;
        }
        return $this->render('default/homepage.html.twig', [
            'planets' => $planets,
            'news' => $news,
            'closestEvent' => $event[0]
        ]);
    }
}
