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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $nasa_api = new NasaAPI();
        $news = $nasa_api->getNews();
//        $nasa_api->saveNasaData($news);
        return $this->render('default/index.html.twig', [
            'planets' => $planets,
            'articles' => $news
        ]);
    }

    /**
     * @Route("/videos/{currentPage}", name="viewing_all_videos")
     * @param $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewingAllVideosAction($currentPage)
    {
        $em = $this->getDoctrine()->getManager();
        $videos = $em
            ->getRepository('AppBundle:Video')
            ->getAllVideos($currentPage);
        $channels = $em
            ->getRepository('AppBundle:Video')
            ->findAllChannels();
        $planets = $em->getRepository('AppBundle:Planet')->findAll();
        $iterator = $videos->getIterator();
        $maxPages = ceil($videos->count()/6);
        if (empty($iterator[0])) {
            throw $this->createNotFoundException('There are no videos on this page!');
        }
        return $this->render('videos/view_all_videos.html.twig',
            [
                'maxPages' => $maxPages,
                'videos' => $iterator,
                'currentPage' => $currentPage,
                'planetsList' => $planets,
                'channels' => $channels
            ]
        );
    }

    /**
     * @Route("/videos/{planetName}/{currentPage}", name="view_videos_by_name")
     * @param $planetName
     * @param $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function planetsAction($planetName, $currentPage)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em
            ->getRepository('AppBundle:Planet')
            ->findAll();
        $channels = $em
            ->getRepository('AppBundle:Video')
            ->findAllChannels();
        $videos = $em
            ->getRepository('AppBundle:Video')
            ->getAllVideosByName($currentPage, $planetName);
        $iterator = $videos->getIterator();
        $maxPages = ceil($videos->count()/6);
        if (empty($iterator[0])) {
            throw $this->createNotFoundException('There are no videos on this page!');
        }
        return $this->render('videos/view_videos_by_name.html.twig',
            [
                'maxPages' => $maxPages,
                'videos' => $iterator,
                'currentPage' => $currentPage,
                'planetsList' => $planets,
                'channels' => $channels,
                'currentPlanet' => $planetName
            ]
        );
    }

    /**
     * @Route("/videos/channel/{channelName}/{currentPage}", name="viewing_all_channel_videos")
     * @param $channelName
     * @param $currentPage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function channelAction($channelName, $currentPage)
    {
        $em = $this->getDoctrine()->getManager();
        $planets = $em
            ->getRepository('AppBundle:Planet')
            ->findAll();
        $channels = $em
            ->getRepository('AppBundle:Video')
            ->findAllChannels();
        $videos = $em
            ->getRepository('AppBundle:Video')
            ->getAllChannelVideos($currentPage, $channelName);
        $iterator = $videos->getIterator();
        $maxPages = ceil($videos->count()/6);
        if (empty($iterator[0])) {
            throw $this->createNotFoundException('There are no videos on this page!');
        }
        return $this->render('videos/view_all_channel_videos.html.twig',
            [
                'maxPages' => $maxPages,
                'videos' => $iterator,
                'currentPage' => $currentPage,
                'planetsList' => $planets,
                'channels' => $channels,
                'currentChannel' => $channelName
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
        $planets = $em
            ->getRepository('AppBundle:Planet')
            ->findAll();
        $planet = $em
            ->getRepository('AppBundle:Planet')
            ->findOneBy(['name' => $planetName]);
        $video = $em
            ->getRepository('AppBundle:Video')
            ->findOneBy(['keyName' => $planetName]);
        if (!$planet) {
            throw $this->createNotFoundException('Ups! No planet found!');
        }
        return $this->render('planet/planet.html.twig', [

            'planet' => $planet,
            'planetsList' => $planets,
            'video' => $video
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
        $article = $em->getRepository('AppBundle:Article')->findOneBy(['id' => $articleID]);
        if (!$article) {
            throw $this->createNotFoundException('Ups! No article found!');
        }

        return $this->render('newsFeed/article.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/news", name="news_list")
     */
    public function showNews()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('newsFeed/news.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/events", name="upcoming_events")
     */
    public function upcomingEventsAction()
    {
        return $this->render('services/upcoming_events.html.twig');
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
