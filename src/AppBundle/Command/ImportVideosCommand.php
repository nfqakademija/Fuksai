<?php
namespace AppBundle\Command;
use AppBundle\Entity\Planet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
        $this->setName('demo:greet')->setDescription('Import youtube videos for articles.')->setHelp('This command finds and imports videos for all articles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $planetNames = $this->getPlanets();
        foreach ($planetNames as $planet => $url) {
            $planet = $this->getVideo($url);
            $result[]= array($url => $planet);
        }
        dump($result);exit;
        foreach ($result as $number => $name ) {
            dump($number);exit;
//            $output->writeln("This is name:".$name."This is Link:".$link);
        }
    }

    private function getPlanets()
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
        $planet = $em->getRepository('AppBundle:Planet')
            ->findAllPlanets();
        $planetsNames = [];
//        dump($planet[1]['name']);exit;
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

    private function insertVideos($data)
    {
        $target = $this->getContainer()
            ->get('doctrine')
            ->getRepository('AppBundle:Planet')
            ->find($data);
    }


}