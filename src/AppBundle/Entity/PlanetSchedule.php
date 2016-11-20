<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanetSchedule
 *
 * @ORM\Table(name="planet_schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetScheduleRepository")
 */
class PlanetSchedule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=255, unique=false)
     */
    private $object;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", length=255)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", length=255)
     */
    private $latitude;

    /**
     * @var int
     *
     * @ORM\Column(name="timezone", type="integer")
     */
    private $timezone;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="rise", type="string", length=255)
     */
    private $rise;

    /**
     * @var string
     *
     * @ORM\Column(name="fall", type="string", length=255)
     */
    private $fall;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return PlanetSchedule
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return PlanetSchedule
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return PlanetSchedule
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set timezone
     *
     * @param integer $timezone
     *
     * @return PlanetSchedule
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return int
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return PlanetSchedule
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set rise
     *
     * @param string $rise
     *
     * @return PlanetSchedule
     */
    public function setRise($rise)
    {
        $this->rise = $rise;

        return $this;
    }

    /**
     * Get rise
     *
     * @return string
     */
    public function getRise()
    {
        return $this->rise;
    }

    /**
     * Set fall
     *
     * @param string $fall
     *
     * @return PlanetSchedule
     */
    public function setFall($fall)
    {
        $this->fall = $fall;

        return $this;
    }

    /**
     * Get fall
     *
     * @return string
     */
    public function getFall()
    {
        return $this->fall;
    }
}

