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
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('default/index.html.twig', [
            'planets' => $planets,
            'articles' => $articles,
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
            'planets' => $planets,
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

    /**
     * @Route("/news/{articleID}", name="show_article")
     */
    public function showArticle($articleID)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->findOneBy(['id' => $articleID]);
        if(!$article){
            throw $this->createNotFoundException('Ups! No article found!');
        }
        return $this->render('news.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/news")
     */
    public function showNews()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('news.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/test")
     */
    public function showTest()
    {
        $nasa_api = new NasaAPI();
        $text = $nasa_api->getNews();

        return $this->render('test.html.twig',[
            'date' => $text['1'],
            'text' => $text['2'],
            'url' => $text['3'],
        ]);
    }

}
