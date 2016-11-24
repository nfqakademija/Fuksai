<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asteroid
 *
 * @ORM\Table(name="asteroid")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AsteroidRepository")
 */
class Asteroid
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="diameter", type="integer")
     */
    private $diameter;

    /**
     * @var int
     *
     * @ORM\Column(name="velocity", type="integer")
     */
    private $velocity;

    /**
     * @var int
     *
     * @ORM\Column(name="miss_distance", type="integer")
     */
    private $missDistance;


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
     * Set name
     *
     * @param string $name
     *
     * @return Asteroid
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set diameter
     *
     * @param integer $diameter
     *
     * @return Asteroid
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

    /**
     * Get diameter
     *
     * @return int
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * Set velocity
     *
     * @param integer $velocity
     *
     * @return Asteroid
     */
    public function setVelocity($velocity)
    {
        $this->velocity = $velocity;

        return $this;
    }

    /**
     * Get velocity
     *
     * @return int
     */
    public function getVelocity()
    {
        return $this->velocity;
    }

    /**
     * Set missDistance
     *
     * @param integer $missDistance
     *
     * @return Asteroid
     */
    public function setMissDistance($missDistance)
    {
        $this->missDistance = $missDistance;

        return $this;
    }

    /**
     * Get missDistance
     *
     * @return int
     */
    public function getMissDistance()
    {
        return $this->missDistance;
    }
}
