<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 11/4/16
 * Time: 7:05 PM
 */

namespace AppBundle\Controller;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Youtube_videos extends Controller
{

    public function getVideoByKey($planetName)
    {
        $url = $this-> getData("https://www.googleapis.com/youtube/v3/search?key=AIzaSyDzxAdrNX8XPi0L4EQQW3kBpUrnHXrbvkM&channelId=UCX6b17PVsYBQ0ip5gyeme-Q&part=id&order=date&maxResults=1&q=".$planetName);
//        if (empty($url['items'])){
//            $video = "https://www.youtube.com/embed/d9TpRfDdyU0";
//            return $video;
//        }
//        else {

        if (isset($url['items'][0])) {
            $videoid = $url['items'][0]['id']['videoId'];
            $video = "https://www.youtube.com/embed/" . $videoid;

            return $video;
        }

        return null;
    }

    public function getData($request)
    {
        $data_json = file_get_contents($request);
        $data_array = json_decode($data_json, true);

        return $data_array;
    }
}