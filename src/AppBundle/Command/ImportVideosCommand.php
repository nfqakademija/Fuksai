<?php
namespace AppBundle\Command;
use AppBundle\Entity\Planet;
use AppBundle\Entity\Videos;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use AppBundle\Repository\PlanetRepository;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/10/16
 * Time: 9:46 AM
 */
class ImportVideosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:import-videos')->setDescription('Import youtube videos for articles.')->setHelp('This command finds and imports videos for all articles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $planetNames = $this->getPlanets();
        foreach ($planetNames as $planet => $url) {
            $planet = $this->getVideo($url);
            $result = array($url => $planet);

            foreach ($result as $name => $url) {
                if ($url == null)
                {
                    $url = "Didnt find video!";
                }
                $videos = $this->createVideos($name, $url);
                $this->insertVideos($videos);
                $output->writeln("Inserting ".$name." video.... ->".$url);
            }

        }
        $output->writeln('All videos inserted!');
    }

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
        foreach ($planet as $planets){
            $planetsNames[] = $planets['name'];
        }
        return $planetsNames;
    }

    private function getVideo($planetName)
    {
            $url = $this->getData("https://www.googleapis.com/youtube/v3/search?key=AIzaSyDzxAdrNX8XPi0L4EQQW3kBpUrnHXrbvkM&channelId=UCX6b17PVsYBQ0ip5gyeme-Q&part=id&order=date&maxResults=1&q=".$planetName);
            if (isset($url['items'][0])) {
                $videoid = $url['items'][0]['id']['videoId'];
                $video = "https://www.youtube.com/embed/" . $videoid;
                return $video;
        }

    }

    private function getData($request)
    {
        $data_json = file_get_contents($request);
        $data_array = json_decode($data_json, true);

        return $data_array;
    }

    private function createVideos($key , $url)
    {
        $Video = new Videos();
        $Video->setKeyName($key)
            ->setPath($url);
        return $Video;
    }

    private function insertVideos($videos)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($videos as $video) {

            $em->persist($video);
        }

        $em->flush();
    }
    private function checkExists($name, $url)
    {
        //Cia pagalvosiu, nes manau reik pakeisti jaigu yra naujas video ar daug geresnis :|
    }



}