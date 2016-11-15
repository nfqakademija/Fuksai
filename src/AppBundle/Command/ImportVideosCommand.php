<?php

/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/10/16
 * Time: 9:46 AM
 */

namespace AppBundle\Command;

use AppBundle\Entity\Planet;
use AppBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use AppBundle\Repository\PlanetRepository;
use Symfony\Component\Console\Output\OutputInterface;

class ImportVideosCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import-videos')
            ->setDescription('Import youtube videos for articles.')
            ->setHelp('This command finds and imports videos for all articles.');
    }

    /**
     * {@inheritdoc}
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $planetNames = $this->getPlanets();

        foreach ($planetNames as $planetName) {
            $video = $this->getVideo($planetName);

            if ($video == null) {
                $output->writeln('Didn\'t find video: '. $planetName);
                continue;

            }

            $data = $this->checkExists($planetName, $video);
            $this->insertVideos($data);

            $output->writeln('Inserting ' . $planetName . ' video... -> ' . $video);
        }

        $output->writeln('All videos inserted!');
    }

    /**
     * @return array
     */
    private function getPlanets()
    {
        $planet = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->createQueryBuilder('planet')
            ->select('planet.name')
            ->getQuery()
            ->execute();//NEDS TO BE CHANGED, CANT GET METHOD FROM REPOSITORY

        $planetsNames = [];

        foreach ($planet as $planets) {
            $planetsNames[] = $planets['name'];
        }

        return $planetsNames;
    }

    /**
     * @param $planetName
     *
     * @return null|string
     */
    private function getVideo($planetName)
    {
        $url = $this->getData("https://www.googleapis.com/youtube/v3/search?key=AIzaSyDzxAdrNX8XPi0L'.
        '4EQQW3kBpUrnHXrbvkM&channelId=UCX6b17PVsYBQ0ip5gyeme-Q&part=id&order=date&maxResults=1&q=".$planetName);

        if (isset($url['items'][0])) {
                $videoid = $url['items'][0]['id']['videoId'];

                return "https://www.youtube.com/embed/" . $videoid;
        }

        return null;

    }

    /**
     * @param $url
     *
     * @return array
     */

    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param $key
     * @param $url
     *
     * @return Video
     */
    private function createVideos($key, $url)
    {
        $video = new Video();
        $video
            ->setKeyName($key)
            ->setPath($url);

        return $video;
    }

    /**
     * @param Video $videos
     */
    private function insertVideos(Video $videos)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($videos);
        $em->flush();

    }

    /**
     * @param $name
     * @param $url
     *
     * @return Video
     */
    private function checkExists($name, $url)
    {
        $em = $this->getContainer()->get('doctrine')
            ->getManager();

        $video = $em->getRepository('AppBundle:Video')
            ->findOneBykeyName($name);

        if (!empty($video)) {
            $video->setpath($url);

            return $video;
        }

            return $this->createVideos($name, $url);
    }
}