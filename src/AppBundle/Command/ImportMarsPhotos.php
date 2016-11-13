<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.13
 * Time: 20.30
 */

namespace AppBundle\Command;


class ImportMarsPhotos
{
    public function func()
    {
        $date = '2016-11-10';
        $data = $this->getData
            ('https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.
            $date
            .'&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');
        dump($data);
        exit();
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }
}