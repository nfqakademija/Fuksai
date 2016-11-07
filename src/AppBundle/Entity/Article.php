<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $explanation;

    /**
     * @ORM\Column(type="string")
     */private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;


    /**
     * Set url
     *
     * @param string $url
     *
     * @return Article
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
 * @return mixed
 */
public function getTitle()
{
    return $this->title;
}/**
 * @param mixed $title
 */
public function setTitle($title)
{
    $this->title = $title;
}/**
 * @return mixed
 */
public function getType()
{
    return $this->type;
}/**
 * @param mixed $type
 */
public function setType($type)
{
    $this->type = $type;
}/**
 * @return mixed
 */
public function getExplanation()
{
    return $this->explanation;
}/**
 * @param mixed $explanation
 */
public function setExplanation($explanation)
{
    $this->explanation = $explanation;
}/**
 * @return mixed
 */
public function getDate()
{
    return $this->date;
}/**
 * @param mixed $date
 */
public function setDate($date)
{
    $this->date = $date;
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


}

