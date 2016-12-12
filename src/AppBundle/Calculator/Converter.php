<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.2
 * Time: 06.23
 */

namespace AppBundle\Calculator;

class Converter
{
    /**
     * @param Coordinate $coordinate
     * @return string
     */
    public static function degToFloat(Coordinate $coordinate)
    {
        $min = $coordinate->getMinutes()/300*5;
        return rtrim($coordinate->getDegrees(), ".0") + round($min, 2);
    }

    /**
     * @param $number
     * @return Coordinate
     */
    public static function floatToDeg($number)
    {
        $degCoordinate = new Coordinate(
            rtrim(floor($number), ".0"),
            rtrim(round(($number - floor($number))/5*3, 2)*100, ".0")
        );
        return $degCoordinate;
    }

    /**
     * @param $value
     * @return int
     */
    public static function getSign($value)
    {
        if ($value < 0) {
            return -1;
        }
        return 1;
    }
}
