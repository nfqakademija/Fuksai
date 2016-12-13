<?php

namespace AppBundle\Calculator;

/**
 * Class Coordinate
 * @package AppBundle\Calculator
 */
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
    public function __construct(int $degrees, int $minutes)
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
    public function setDegrees(int $degrees)
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
    public function setMinutes(int $minutes)
    {
        $this->minutes = $minutes;
    }
}
