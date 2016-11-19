<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $news = $em->getRepository('AppBundle:Article')->findAll();

        $nasa_api = new NasaAPI();
        $nasa_news = $nasa_api->getNews();
//        $nasa_api->saveNasaData($news);

        return $this->render('default/index.html.twig', [
            'planets' => $planets,
            'news' => $news,
            'nasa_articles' => $nasa_news
        ]);
    }

    /**
     * @Route("/list")
     */
    public function planetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();


        return $this->render(
            'planet/planets_list.html.twig',
            [
            'planets' => $planets
            ]
        );
    }

    /**
     * @Route("/planets/{planetName}", name="show_planet")
     * @param $planetName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showPlanet($planetName)
    {
        $em = $this->getDoctrine()->getManager();
        $planetArticles = $em->getRepository('AppBundle:PlanetArticle')->findBy(['planet' => $planetName]);
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $planet = $em->getRepository('AppBundle:Planet')->findOneBy(['name' => $planetName]);
        $video = $em->getRepository('AppBundle:Video')->findOneBy(['keyName' => $planetName]);
        if (!$planet) {
            throw $this->createNotFoundException('Ups! No planet found!');
        }
        return $this->render('planet/planet.html.twig', [

            'planet' => $planet,
            'planetsList' => $planets,
            'video' => $video,
            'planetArticles' => $planetArticles,
        ]);
    }

    /**
     * @Route("/news/{articleID}", name="show_article")
     * @param $articleID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showArticle($articleID)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $article = $em->getRepository('AppBundle:Article')->findOneBy(['articleId' => $articleID]);

        // if could find article from Article entity then get from PlanetArticle entity
        if (!$article) {
            $article = $em->getRepository('AppBundle:PlanetArticle')->findOneBy(['articleId' => $articleID]);

            if (!$article) {
                throw $this->createNotFoundException('Ups! No article found!');
            }
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
    public function showNews()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('newsFeed/news.html.twig', [
            'articles' => $articles,
            'planetsList' => $planets,
        ]);
    }

    /**
     * @Route("/test")
     */
    public function showTest()
    {
        $nasa_api = new NasaAPI();
        $text = $nasa_api->getNews();

        return $this->render(
            'nasaApi/test.html.twig',
            [
                'text' => $text,
            ]
        );
    }

    /**
     * @Route("/events", name="upcoming_events")
     */
    public function upcomingEventsAction()
    {

        return $this->render('services/upcoming_events.html.twig');
    }

    /**
     * @Route("/videos", name="astronomical_videos")
     */
    public function astronomicalVideosAction()
    {

        return $this->render('services/videos.html.twig');
    }

    /**
     * @Route("/articles", name="astronomical_articles")
     */
    public function astronomicalArticlesAction()
    {

        return $this->render('services/articles.html.twig');
    }

    /**
     * @Route("/astronomy-picture", name="astronomy_picture")
     */
    public function astronomyPictureAction()
    {

        return $this->render('services/picture_of_the_day.html.twig');
    }

    /**
     * @Route("/solar-system-display", name="solar_system_display")
     */
    public function solarSystemDisplayAction()
    {

        return $this->render('services/solar_system_display.html.twig');
    }

    /**
     * @Route("/constellation-display", name="constellation_display")
     */
    public function constellationDisplayAction()
    {
        return $this->render('services/constellation.html.twig');
    }

    /**
     * @Route("/constellation-position-calculator", name="constellation_position_calculator")
     */
    public function constellationPositionCalculatorAction()
    {

        return $this->render('services/constellation_calculator.html.twig');
    }

    /**
     * @Route("/planet-position-calculator", name="planet_position_calculator")
     */
    public function planetPositionCalculatorAction()
    {

        return $this->render('services/planet_calculator.html.twig');
    }
}
