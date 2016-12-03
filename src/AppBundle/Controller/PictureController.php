<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PictureController extends Controller
{
    /**
     * @Route("/astronomy-picture-of-the-day/{picture}", name="astronomy_picture")
     * @param $picture
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pictureAction($picture)
    {
        $em = $this->getDoctrine()->getManager();
        $pictures = $em->getRepository('AppBundle:Picture')->findAll();

        return $this->render('picture/astronomy_picture.html.twig', [
            'pictures' => $pictures,
            'picture' => $picture,
        ]);
    }

    /**
     * @Route("/astronomy-pictures-of-the-day", name="astronomy_pictures")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allPicturesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pictures = $em->getRepository('AppBundle:Picture')->findAll();

        return $this->render('picture/all_astronomy_pictures.html.twig', [
            'pictures' => $pictures,
        ]);
    }
}
