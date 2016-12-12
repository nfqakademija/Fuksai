<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscriber
 *
 * @ORM\Table(name="subscriber")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberRepository")
 */
class Subscriber
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
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="key_name", type="string", unique=true)
     */
    private $keyName;


    /**
     * @return string
     */
    public function getKeyName(): string
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
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
