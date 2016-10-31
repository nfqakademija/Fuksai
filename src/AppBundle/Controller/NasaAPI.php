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
    var $api_key = "Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s";

    public function getNewsArray()
    {

    }

    public function getData($request)
    {
        $data_json = file_get_contents($request);
        $data_array = json_decode($data_json, true);

        return $data_json;
    }
}