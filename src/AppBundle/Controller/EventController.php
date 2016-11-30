<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/30/16
 * Time: 3:50 PM
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EventController extends Controller
{
    /**
     * @Route("/events", name="view_events")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function EventAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findAll();

        return $this->render('event/view_events.html.twig', [
            'events' => $events
        ]);
    }
}
