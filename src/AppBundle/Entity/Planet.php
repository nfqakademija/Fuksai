<?php

/**
 * Created by PhpStorm.
 * User: artur
 * Date: 10/29/16
 * Time: 1:36 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="planet")
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
     * @return mixed
     */
    public function getDiscovery()
    {
        return $this->discovery;
    }

    /**
     * @param mixed $discovery
     */
    public function setDiscovery($discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @return mixed
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * @param mixed $diameter
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;
    }

    /**
     * @return mixed
     */
    public function getOrbit()
    {
        return $this->orbit;
    }

    /**
     * @param mixed $orbit
     */
    public function setOrbit($orbit)
    {
        $this->orbit = $orbit;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getNamedAs()
    {
        return $this->namedAs;
    }

    /**
     * @param mixed $namedAs
     */
    public function setNamedAs($namedAs)
    {
        $this->namedAs = $namedAs;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}