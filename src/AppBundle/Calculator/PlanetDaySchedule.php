<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.12
 * Time: 20.26
 */

namespace AppBundle\Calculator;

class PlanetDaySchedule
{
    /**
     * @var string
     */
    private $rise;

    /**
     * @var string
     */
    private $fall;

    /**
     * PlanetDaySchedule constructor.
     * @param string $rise
     * @param string $fall
     */
    public function __construct($rise, $fall)
    {
        $this->rise = $rise;
        $this->fall = $fall;
    }

    /**
     * @return string
     */
    public function getRise()
    {
        return $this->rise;
    }

    /**
     * @param string $rise
     */
    public function setRise($rise)
    {
        $this->rise = $rise;
    }

    /**
     * @return string
     */
    public function getFall()
    {
        return $this->fall;
    }

    /**
     * @param string $fall
     */
    public function setFall($fall)
    {
        $this->fall = $fall;
    }
}
