<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class NewsController
 * @package AppBundle\Controller
 */
class NewsController extends Controller
{
    /**
     * @Route("/news/{id}", name="show_article")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $article = $em->getRepository('AppBundle:Article')->findOneBy(['id' => $id]);

        if (!$article) {
            throw $this->createNotFoundException('Ups! No article found!');
        }

        return $this->render('newsFeed/article.html.twig', [
            'article' => $article,
            'planetsList' => $planets,
        ]);
    }

    /**
     * @Route("/news", name="news_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $articles = $em->getRepository('AppBundle:Article')->findAllByDate();

        return $this->render('newsFeed/news.html.twig', [
            'articles' => $articles,
            'planetsList' => $planets,
        ]);
    }

    /**
     * @Route("/planetArticles/{planet}", name="planet_articles")
     * @param $planet
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planetArticlesAction($planet)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $planetArticles = $em->getRepository('AppBundle:Article')->findBy(['planet' => $planet]);

        return $this->render('newsFeed/planet_articles.html.twig', [
            'planetArticles' => $planetArticles,
            'planetsList' => $planets,
            'planet' => $planet,
        ]);
    }
}
