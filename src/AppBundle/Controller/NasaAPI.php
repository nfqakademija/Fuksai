<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.10.31
 * Time: 23.31
 */

namespace AppBundle\Controller;


class NasaAPI
{
    public function getNews()
    {
        $news = $this->getData('https://api.nasa.gov/planetary/apod?api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');

        $data['1'] = $news["date"];
        $data['2'] = $news["explanation"];
        $data['3'] = $news["url"];

        return $data;
    }

    public function getData($request)
    {
        $data_json = file_get_contents($request);
        $data_array = json_decode($data_json, true);

        return $data_array;
    }
}