<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoverPhoto
 *
 * @ORM\Table(name="rover_photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoverPhotoRepository")
 */
class RoverPhoto
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
     * @ORM\Column(name="img_src", type="string", length=255)
     */
    private $imgSrc;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="rover", type="string", length=255)
     */
    private $rover;

    /**
     * @var string
     *
     * @ORM\Column(name="camera", type="string", length=255)
     */
    private $camera;


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
     * Set imgSrc
     *
     * @param string $imgSrc
     *
     * @return RoverPhoto
     */
    public function setImgSrc($imgSrc)
    {
        $this->imgSrc = $imgSrc;

        return $this;
    }

    /**
     * Get imgSrc
     *
     * @return string
     */
    public function getImgSrc()
    {
        return $this->imgSrc;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return RoverPhoto
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
     * Set rover
     *
     * @param string $rover
     *
     * @return RoverPhoto
     */
    public function setRover($rover)
    {
        $this->rover = $rover;

        return $this;
    }

    /**
     * Get rover
     *
     * @return string
     */
    public function getRover()
    {
        return $this->rover;
    }

    /**
     * Set camera
     *
     * @param string $camera
     *
     * @return RoverPhoto
     */
    public function setCamera($camera)
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * Get camera
     *
     * @return string
     */
    public function getCamera()
    {
        return $this->camera;
    }
}
