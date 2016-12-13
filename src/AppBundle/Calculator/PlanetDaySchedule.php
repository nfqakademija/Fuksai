<?php

namespace AppBundle\Calculator;

/**
 * Class PlanetDaySchedule
 * @package AppBundle\Calculator
 */
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
    public function __construct(string $rise, string $fall)
    {
        $this->rise = $rise;
        $this->fall = $fall;
    }

    /**
     * @return string
     */
    public function getRise(): string
    {
        return $this->rise;
    }

    /**
     * @param string $rise
     */
    public function setRise(string $rise)
    {
        $this->rise = $rise;
    }

    /**
     * @return string
     */
    public function getFall(): string
    {
        return $this->fall;
    }

    /**
     * @param string $fall
     */
    public function setFall(string $fall)
    {
        $this->fall = $fall;
    }
}
