<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class VideosController
 * @package AppBundle\Controller
 */
class VideosController extends Controller
{
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
        return $this->render('videos/view_all_videos.html.twig', [
            'maxPages' => $maxPages,
            'videos' => $iterator,
            'currentPage' => $currentPage,
            'planetsList' => $planets,
            'channels' => $channels
        ]);
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
        return $this->render('videos/view_videos_by_name.html.twig', [
            'maxPages' => $maxPages,
            'videos' => $iterator,
            'currentPage' => $currentPage,
            'planetsList' => $planets,
            'channels' => $channels,
            'currentPlanet' => $planetName
        ]);
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
        return $this->render('videos/view_all_channel_videos.html.twig', [
            'maxPages' => $maxPages,
            'videos' => $iterator,
            'currentPage' => $currentPage,
            'planetsList' => $planets,
            'channels' => $channels,
            'currentChannel' => $channelName
        ]);
    }
}
