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
use Symfony\Component\Console\Input\InputInterface;
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
        $planetNames = $this->getKeyNames();//Returns planet names
        $channels = $this->getContainer()->getParameter('channel_ids');//Returns channels names and channel Ids from parameters.yml

        foreach($channels as $channelName => $channelurl)
        {
            $videos = $this->getVideo($planetNames, $channelurl);//Returns videos
            foreach($videos as $video)
            {
                $data = $this->checkExists($video['name'], $video['path']);//Checks if videos already exists in database
                if ($data == 1)
                {
                    $output->writeln('Video: ' . $video['name'] . ' ,with path: ' . $video['path'] . ' alredy exists in database');
                    continue;
                }

                $data = $this->createVideos($video['name'], $video['path'], $channelName);//Creates video's data
                $this->getContainer()
                    ->get('doctrine')
                    ->getRepository('AppBundle:Video')
                    ->save($data);//Saves that data
                $output->writeln('Inserting ' . $video['name'] . ' video url... -> ' . $video['path']);
            }

            $output->writeln('All ' . $channelName . ' videos inserted!');
        }
    }

    /**
     * @return array
     */
    private function getKeyNames()
    {
        $planets = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->findPlanets();//Returns all planet
        $planetsNames = [];
        foreach($planets as $planet)
        {
            $planetsNames[] = $planet['keyName'];//Saves planet keyNames in array
        }

        return $planetsNames;
    }

    /**
     * @param $planetName
     * @param $channelurl
     * @return array
     */
    private function getVideo($planetName, $channelurl)
    {
        $apiKey = $this->getContainer()->getParameter('youtube_api_key');//Return youtube Api key from parameters.yml
        $url = $this->getPaths($channelurl, $apiKey, $planetName);//Gets videos id
        foreach($planetName as $key => $name)
        {
            if (isset($url[$key]))//checks if key exists, if in found data there are videos
            {
                $items = $url[$key]['items'];
                foreach($items as $video)
                {
                    $videoId = $video['id']['videoId'];
                    $videosPath = array(
                        'name' => $name,
                        'path' => "https://www.youtube.com/embed/" . $videoId
                    );//Creates array for each found video
                    $master[] = $videosPath;
                }
            }
        }

        return $master;
    }

    /**
     * @param $url
     * @return mixed
     */
    private function getData($url)//retruns json data
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        return $data;
    }

    /**
     * @param $key
     * @param $url
     * @param $channelName
     * @return Video
     */
    private function createVideos($key, $url, $channelName)//Creates videos that will be pushed to database
    {
        $video = new Video();
        $video->setKeyName($key)->setPath($url)->setChannelName($channelName);
        return $video;
    }

    /**
     * @param $name
     * @param $path
     * @return int|null
     */
    private function checkExists($name, $path)//Checks if videos already exists
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        $url = $em->getRepository('AppBundle:Video')
            ->findOneBy(
                array(
            'keyName' => $name,
            'path' => $path
        ));
        if ($url == null)
        {
            return null;
        }
        else
        {
            return 1;
        }
    }

    /**
     * @param $url
     * @param $apiKey
     * @param $planetName
     * @return array
     */
    private function getPaths($url, $apiKey, $planetName)//gets videos for every planet
    {
        foreach($planetName as $planet)
        {
            $channelPath[] = $this
                ->getData(sprintf('https://www.googleapis.com/youtube/v3/search?key=%s&channelId=%s&part=id&order=date&maxResults=3&q=%s',
                $apiKey,
                $url,
                $planet
            ));
            $result = $channelPath;
        }

        return $result;
    }
}
