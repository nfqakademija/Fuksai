<?php

namespace AppBundle\Command;

use AppBundle\Entity\Planet;
use AppBundle\Entity\Video;
use AppBundle\ExceptionLib\NoAPIParameterException;
use AppBundle\ExceptionLib\NoApiResponseException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
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
            try {
                $videos = $this->getVideo($planetNames, $channelurl);
            } catch (NoApiResponseException $exception) {
                $output->writeln($exception->getMessage());
                return 1;
            } catch (NoAPIParameterException $exception) {
                $output->writeln($exception->getMessage());
                return 1;
            }
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
                $data = $this->createVideos(
                    $video['name'],
                    $video['path'],
                    $channelName,
                    $video['image'],
                    $video['title'],
                    $video['description']
                );
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
    private function getKeyNames(): array
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
     * @param $channelURL
     * @return array
     */
    private function getVideo(array $planetName, string $channelURL): array
    {
        $master = array();
        //Return youtube Api key from parameters.yml
        $apiKey = $this->getContainer()->getParameter('youtube_api_key');
        //Gets videos id
        $url = $this->getPaths($channelURL, $apiKey, $planetName);

        try {
            foreach ($planetName as $key => $name) {
                //checks if key exists, if in found data there are videos
                if (isset($url[$key])) {
                    $items = $url[$key]['items'];
                    foreach ($items as $video) {
                        //Creates array for each found video
                        $videoId = $video['id']['videoId'];
                        $videoImage = $video['snippet']['thumbnails']['medium']['url'];
                        $videoTitle = $video['snippet']['title'];
                        $videoDescription = $video['snippet']['description'];
                        $videosPath = array(
                            'name' => $name,
                            'path' => "https://www.youtube.com/embed/" . $videoId,
                            'image' => $videoImage,
                            'title' => $videoTitle,
                            'description' => $videoDescription
                        );
                        $master[] = $videosPath;
                    }
                }
            }
        } catch (Exception $exception) {
            throw new NoAPIParameterException('Some parameters are missing');
        }

        return $master;
    }

    /**
     * Returns JSON data.
     *
     * @param $url
     * @return array
     */
    private function getData(string $url)
    {
        try {
            $json = file_get_contents($url);
            $data = json_decode($json, true);
        } catch(Exception $exception) {
            throw new NoApiResponseException('Youtube did not respond');
        }
        return $data;
    }

    /**
     * Creates videos that will be pushed to database.
     *
     * @param $key
     * @param $url
     * @param $channelName
     * @param $image
     * @param $title
     * @param $description
     * @return Video
     */
    private function createVideos($key, $url, $channelName, $image, $title, $description): Video
    {
        $video = new Video();
        $video
            ->setKeyName($key)
            ->setPath($url)
            ->setChannelName($channelName)
            ->setImage($image)
            ->setTitle($title)
            ->setDescription($description);

        return $video;
    }

    /**
     * Checks if videos already exists.
     *
     * @param $name
     * @param $path
     * @return int|null
     */
    private function checkExists(string $name, string $path)
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
     * Gets videos for every planet.
     *
     * @param $url
     * @param $apiKey
     * @param $planetName
     * @return array
     */
    private function getPaths(string $url, string $apiKey, array $planetName): array
    {
        $result = array();
        $channelPath = array();
        foreach ($planetName as $planet) {
            $youtube = 'https://www.googleapis.com/youtube/v3/search?';
            $channelPath[] = $this
                ->getData(
                    sprintf(
                        '%skey=%s&channelId=%s&part=snippet&order=date&maxResults=4&q=%s',
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
