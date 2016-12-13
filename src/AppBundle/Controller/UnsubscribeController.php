<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UnsubscribeController
 * @package AppBundle\Controller
 */
class UnsubscribeController extends Controller
{
    /**
     * @Route("/unsubscribe/user={userKeyName}", name="show_unsubscribe")
     * @param $userKeyName
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function showUnsubscribe($userKeyName)
    {
        $em = $this->getDoctrine()->getManager();
        $userToRemove = $em->getRepository('AppBundle:Subscriber')->findOneBy(['keyName' => $userKeyName]);

        $em->remove($userToRemove);
        $em->flush();

        return $this->render('subscribe/subscribe_info.html.twig', [
            'status' => 'Email '.$userToRemove->getEmail().' has been removed from the subscriber list!',
            'page' => '',
        ]);
    }
}
