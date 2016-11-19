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
            dump($videos);exit;
            foreach ($videos as $video) {
                if ($video == null) {
                    $output->writeln('Didn\'t find video: '. $video['name'] . ", in youtube channel: " . $channelName);
                }

//                $data = $this->checkExists($planetName, $video);
                $data = $this->createVideos($video['name'] , $video['path'], $channelName);
                $this
                    ->getContainer()
                    ->get('doctrine')
                    ->getRepository('AppBundle:Video')
                    ->save($data);

                $output->writeln('Inserting ' . $video['name'] . ' video url... -> ' . $video['path']);
            }
            $output->writeln('All ' .$channelName . ' videos inserted!');
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
        foreach ($planetName as $name) {
            foreach ($url as $urli) {
            if (isset($urli['items'])) {
                $url2 = $urli['items'];

                        foreach ($url2 as $video)
                        {
                            $videosPath = array();
                            $videoId = $video['id']['videoId'];
                        $videosPath[] = array(
                            'name' => $name,
                            'path' => "https://www.youtube.com/embed/" . $videoId
                        );
                            $master[] = $videosPath;
                }
                }
            }
            dump($master);exit;
            return $pureData;
        }
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
    private function checkExists($name, $url)
    {
        $em = $this->getContainer()->get('doctrine')
            ->getManager();

        $video = $em->getRepository('AppBundle:Video')
            ->findOneBykeyName($name);

        if (!empty($video)){
            $video->setpath($url);

            return $video;
        }

        return $this->createVideos($name, $url);
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
                        'https://www.googleapis.com/youtube/v3/search?key=%s&channelId=%s&part=id&order=date&maxResults=1&q=%s',
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
