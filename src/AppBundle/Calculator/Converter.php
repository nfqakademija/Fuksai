<?php

namespace AppBundle\Calculator;

/**
 * Class Converter
 * @package AppBundle\Calculator
 */
class Converter
{
    /**
     * @param Coordinate $coordinate
     * @return string
     */
    public static function degToFloat(Coordinate $coordinate): string
    {
        $min = $coordinate->getMinutes()/300*5;
        return rtrim($coordinate->getDegrees(), ".0") + round($min, 2);
    }

    /**
     * @param float $number
     * @return Coordinate
     */
    public static function floatToDeg(float $number): Coordinate
    {
        $degCoordinate = new Coordinate(
            rtrim(floor($number), ".0"),
            rtrim(round(($number - floor($number))/5*3, 2)*100, ".0")
        );
        return $degCoordinate;
    }

    /**
     * @param float $value
     * @return int
     */
    public static function getSign(float $value): int
    {
        if ($value < 0) {
            return -1;
        }
        return 1;
    }
}
