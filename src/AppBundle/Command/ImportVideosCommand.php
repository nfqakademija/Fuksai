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
        //Returns planet names
        $planetNames = $this->getKeyNames();
        //Returns channels names and channel Ids from parameters.yml
        $channels = $this->getContainer()->getParameter('channel_ids');

        foreach ($channels as $channelName => $channelurl) {
            //Returns videos
            $videos = $this->getVideo($planetNames, $channelurl);
            foreach ($videos as $video) {
                //Checks if videos already exists in database
                $data = $this->checkExists($video['name'], $video['path']);
                if ($data == 1) {
                    $output
                        ->writeln(
                            'Video: ' . $video['name'] .
                            ' ,with path: ' . $video['path'] .
                            ' alredy exists in database'
                        );
                    continue;
                }
                //Creates video's data
                $data = $this->createVideos($video['name'], $video['path'], $channelName);
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
        //Returns all planet
        $planets = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->findPlanets();
        $planetsNames = [];
        foreach ($planets as $planet) {
            //Saves planet keyNames in array
            $planetsNames[] = $planet['keyName'];
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
        //Return youtube Api key from parameters.yml
        $apiKey = $this->getContainer()->getParameter('youtube_api_key');
        //Gets videos id
        $url = $this->getPaths($channelurl, $apiKey, $planetName);
        foreach ($planetName as $key => $name) {
            //checks if key exists, if in found data there are videos
            if (isset($url[$key])) {
                $items = $url[$key]['items'];
                foreach ($items as $video) {
                    //Creates array for each found video
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
     * @return mixed
     */
    //retruns json data
    private function getData($url)
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
    //Creates videos that will be pushed to database
    private function createVideos($key, $url, $channelName)
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
    //Checks if videos already exists
    private function checkExists($name, $path)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        $url = $em->getRepository('AppBundle:Video')
            ->findOneBy(
                array(
                    'keyName' => $name,
                    'path' => $path
                )
            );
        if ($url == null) {
            return null;
        } else {
            return 1;
        }
    }

    /**
     * @param $url
     * @param $apiKey
     * @param $planetName
     * @return array
     */
    //gets videos for every planet
    private function getPaths($url, $apiKey, $planetName)
    {
        foreach ($planetName as $planet) {
            $youtube = 'https://www.googleapis.com/youtube/v3/search?';
            $channelPath[] = $this
                ->getData(
                    sprintf(
                        '%skey=%s&channelId=%s&part=id&order=date&maxResults=4&q=%s',
                        $youtube,
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
