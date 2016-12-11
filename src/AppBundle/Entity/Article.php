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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
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
    public function setUrlToImage(string $urlToImage)
    {
        $this->urlToImage = $urlToImage;
    }

    /**
     * @return string
     */
    public function getUrlToImage(): string
    {
        return $this->urlToImage;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $this->getDescriptionWithoutWhitespaces($description);
    }

    /**
     * @param string $description
     * @return string description with removed whitespaces from the beginning and end of a description
     */
    private function getDescriptionWithoutWhitespaces(string $description): string
    {
        return trim($description);
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
    public function setPublishDateString(string $newPublishDate)
    {
        // Formatted date string
        $pub_date = $this->getFormattedDateString($newPublishDate);

        $this->publishDate = new \DateTime($pub_date);
    }

    /**
     * @param string $dateString
     * @return string
     */
    private function getFormattedDateString(string $dateString): string
    {
        return date('Y-m-d', strtotime($dateString));
    }

    /**
     * @param \DateTime $publishDate
     */
    public function setPublishDate(\DateTime $publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return string
     */
    public function getPublishDateString(): string
    {
        return (date('Y-m-d', $this->publishDate->getTimestamp()));
    }

    /**
     * @return \DateTime
     */
    public function getPublishDate(): \DateTime
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
