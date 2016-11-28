<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Faker\Provider\cs_CZ\DateTime;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $urlToImage;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $planet;

    //                      GETTERS AND SETTERS

    /**
     * @return string
     */
    public function getPlanet(): string
    {
        return $this->planet;
    }

    /**
     * @param string $planet
     */
    public function setPlanet(string $planet)
    {
        $this->planet = $planet;
    }

    /**
     * @param string $urlToImage
     */
    public function setUrlToImage($urlToImage)
    {
        $this->urlToImage = $urlToImage;
    }

    /**
     * @return string
     */
    public function getUrlToImage()
    {
        return $this->urlToImage;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $newPublishDate
     */
    public function setPublishStringDate($newPublishDate)
    {
        $pub_date = date('d/m/Y', strtotime($newPublishDate));

        dump($pub_date);
        exit;

        // date string converted to a specific format
        $pub_date = substr($newPublishDate, 0, 10);

        // DateTime object converted from string
        $pub_date = date_create_from_format('Y-m-d', $pub_date);

        $this->publishDate = $pub_date;
    }

    /**
     * @param DateTime $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return string
     */
    public function getPublishDateInString()
    {
        return (date('Y-m-d', $this->publishDate->getTimestamp()));
    }

    /**
     * @return DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
