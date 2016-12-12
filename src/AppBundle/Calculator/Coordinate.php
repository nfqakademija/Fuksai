<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.2
 * Time: 06.25
 */

namespace AppBundle\Calculator;

class Coordinate
{
    /**
     * @var int
     */
    private $degrees;

    /**
     * @var int
     */
    private $minutes;

    /**
     * Coordinate constructor.
     * @param int $degrees
     * @param int $minutes
     */
    public function __construct($degrees, $minutes)
    {
        $this->degrees = $degrees;
        $this->minutes = $minutes;
    }

    /**
     * @return int
     */
    public function getDegrees()
    {
        return $this->degrees;
    }

    /**
     * @param int $degrees
     */
    public function setDegrees($degrees)
    {
        $this->degrees = $degrees;
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @param int $minutes
     */
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;
    }
}
