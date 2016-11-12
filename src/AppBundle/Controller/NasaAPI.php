<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.10.31
 * Time: 23.31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

class NasaAPI extends Controller
{
    public function getNews()
    {
        $news = $this->getData('https://api.nasa.gov/planetary/apod?api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
        $data['date'] = $news["date"];
        $data['explanation'] = $news["explanation"];
        $data['url'] = $news["url"];
        $data['title'] = $news["title"];
        $data['type'] = $news["media_type"];
        if ($data['type'] == "video"){
            $data['url'] = "<iframe width='560' height='315' src='".$data['url']."' frameborder='0' allowfullscreen></iframe>";
            return $data;
        }
        else {
            $data['url'] = "<img  style='width='auto' height='290px'' src=".$data['url']." >";
            return $data;
        }

        return $data;
    }
//    public function saveNasaData($data)
//    {
//        $savingData = new Article();
//        $savingData->setDate($data['date']);
//        $savingData->setExplanation($data['explanation']);
//        $savingData->setTitle($data['title']);
//        $savingData->setType($data['type']);
//        $savingData->setUrl($data['url']);
//        $em = $this->getDoctrine()->getManager();
//
//        $em->persist($savingData);
//        $em->flush();
//    }

    public function getData($request)
    {
        $data_json = file_get_contents($request);
        $data_array = json_decode($data_json, true);

        return $data_array;
    }
}