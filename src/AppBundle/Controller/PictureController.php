<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PictureController extends Controller
{
    /**
     * @Route("/astronomy-picture-of-the-day", name="astronomy_picture")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function picturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pictures = $em->getRepository('AppBundle:Picture')->findAll();

        return $this->render('picture/astronomy_picture.html.twig', [
            'pictures' => $pictures,
        ]);
    }
}
