<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EventController extends Controller
{
    /**
     * @Route("/events", name="view_events")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findAll();
        $page = 'events';

        return $this->render('event/view_events.html.twig', [
            'events' => $events,
            'page' => $page
        ]);
    }
}
