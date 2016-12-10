<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Planet
 *
 * @ORM\Table(name="planet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetRepository")
 * @UniqueEntity("keyName")
 */
class Planet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="string")
     */
    private $discovery;

    /**
     * @ORM\Column(type="string")
     */
    private $namedAs;

    /**
     * @ORM\Column(type="string")
     */
    private $diameter;
    /**
     * @ORM\Column(type="string")
     */
    private $orbit;
    /**
     * @ORM\Column(type="string")
     */
    private $day;

    /**
     * @ORM\Column(type="string")
     */
    private $keyName;

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param string $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }


    /**
     * @return string
     */
    public function getDiscovery()
    {
        return $this->discovery;
    }

    /**
     * @param string $discovery
     */
    public function setDiscovery($discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @return string
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * @param string $diameter
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;
    }

    /**
     * @return string
     */
    public function getOrbit()
    {
        return $this->orbit;
    }

    /**
     * @param string $orbit
     */
    public function setOrbit($orbit)
    {
        $this->orbit = $orbit;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getNamedAs()
    {
        return $this->namedAs;
    }

    /**
     * @param string $namedAs
     */
    public function setNamedAs($namedAs)
    {
        $this->namedAs = $namedAs;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
