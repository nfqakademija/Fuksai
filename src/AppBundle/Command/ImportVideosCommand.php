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

/**
 * Class ImportVideosCommand
 * @package AppBundle\Command
 */
class ImportVideosCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:import:videos')
            ->setDescription('Import youtube videos for articles.')
            ->setHelp('This command finds and imports videos for all articles.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $planetNames = $this->getPlanets();
        $channelIDs = $this->getContainer()->getParameter('channel_ids');
        $channels = $this->getChannels($channelIDs);
        foreach ($channels as $channelName => $channelurl) {
            $videos = $this->getVideo($planetNames, $channelurl);
            foreach ($videos as $video) {
                $data = $this->checkExists($video['name'], $video['path']);

                if ($data == 1) {
                    $output->writeln('Video: ' . $video['name'] . ' ,with path: ' . $video['path'] . ' alredy exists in database');
                    continue;
                }
                    $data = $this->createVideos($video['name'], $video['path'], $channelName);
                    $this
                        ->getContainer()
                        ->get('doctrine')
                        ->getRepository('AppBundle:Video')
                        ->save($data);

                    $output->writeln('Inserting ' . $video['name'] . ' video url... -> ' . $video['path']);

            }
            $output->writeln('All ' . $channelName . ' videos inserted!');
        }


    }

    /**
     * @return array
     */
    private function getPlanets()
    {
        $planets = $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->findPlanets();

        $planetsNames = [];

        foreach ($planets as $planet){
            $planetsNames[] = $planet['name'];
        }

        return $planetsNames;
    }

    /**
     * @param $planetName
     *
     * @return null|string
     */
    private function getVideo($planetName, $channelurl)
    {
        $apiKey = $this->getContainer()->getParameter('youtube_api_key');
        $url = $this->getPaths($channelurl, $apiKey, $planetName);
        foreach ($planetName as $key => $name) {
            if (isset($url[$key])) {
                $url2 = $url[$key]['items'];
                $videosPath = array();
                foreach ($url2 as $video) {
                    $videoId = $video['id']['videoId'];
                    $videosPath = array(
                        'name' => $name,
                        'path' => "https://www.youtube.com/embed/" . $videoId
                    );
                    $master[] = $videosPath;
                }
            }
        }
        return $master;
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
    private function createVideos($key , $url, $channelName)
    {
        $video = new Video();
        $video
            ->setKeyName($key)
            ->setPath($url)
            ->setChannelName($channelName);

        return $video;
    }

    /**
     * @param $name
     * @param $url
     *
     * @return Video
     */
    private function checkExists($name, $path)
    {
        $em = $this->getContainer()->get('doctrine')
            ->getManager();
        $url = $em->getRepository('AppBundle:Video')->findOneBy(
            array(
                'keyName' => $name,
                'path' => $path
            )
        );
        if ($url == null){
            return null;
        }else{
            return 1;
        }
    }

    private function getChannels($channelIds)
    {
        $channels = array();
        $channelIds = explode(',', $channelIds);
        foreach ($channelIds as $channelId)
        {
            $structure = explode(':', $channelId);
            $channels[$structure[0]] = $structure[1];
        }
        return $channels;

    }

    private function getPaths($url, $apiKey, $planetName)
    {
        foreach ($planetName as $planet) {
            $channelPath[] = $this
                ->getData(
                    sprintf(
                        'https://www.googleapis.com/youtube/v3/search?key=%s&channelId=%s&part=id&order=date&maxResults=2&q=%s',
                        $apiKey,
                        $url,
                        $planet
                    )
                );
            $result = $channelPath;
        }
        return $result;
    }
}
