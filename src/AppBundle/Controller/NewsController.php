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
        $filters = $em->getRepository('AppBundle:Planet')->findAll();
        $article = $em->getRepository('AppBundle:Article')->findOneBy(['id' => $id]);
        $page = 'news';

        if (!$article) {
            throw $this->createNotFoundException('Ups! No article found!');
        }

        return $this->render('newsFeed/article.html.twig', [
            'article' => $article,
            'filters' => $filters,
            'page' => $page

        ]);
    }

    /**
     * @Route("/news/page/{number}", name="news_list")
     * @param $number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction($number)
    {
        $em = $this->getDoctrine()->getManager();
        $filters = $em->getRepository('AppBundle:Planet')->findAll();
        $articles = $em->getRepository('AppBundle:Article')->findAllByDate();
        $page = 'news';

        return $this->render('newsFeed/news.html.twig', [
            'articles' => $articles,
            'filters' => $filters,
            'pageNumber' => $number,
            'page' => $page
        ]);
    }

    /**
     * @Route("/planetArticles/{planet}/page/{number}", name="planet_articles")
     * @param $planet
     * @param $number
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planetArticlesAction($planet, $number)
    {
        $em = $this->getDoctrine()->getManager();
        $filters = $em->getRepository('AppBundle:Planet')->findAll();
        $planetArticles = $em->getRepository('AppBundle:Article')->findBy(['planet' => $planet]);
        $page = 'news';

        return $this->render('newsFeed/planet_articles.html.twig', [
            'planetArticles' => $planetArticles,
            'filters' => $filters,
            'planet' => $planet,
            'pageNumber' => $number,
            'page' => $page
        ]);
    }
}
