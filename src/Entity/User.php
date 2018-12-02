<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"parent" = "UserParent", "teacher" = "UserTeacher"})
 *
 * @UniqueEntity(fields={"mail"}, message="This specified email {{ value }} already exists", groups={"user_create"})
 * @JMS\ExclusionPolicy("all")
 */
abstract class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @SWG\Property(description="Unique id of the user.")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @SWG\Property(description="Unique apiToken of the user.")
     */
    private $apiToken;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create"})
     *
     * @SWG\Property(description="First name of the user.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create"})
     *
     * @SWG\Property(description="Last name of the user.")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create", "user_login"})
     *
     * @SWG\Property(description="Password of the user.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create", "user_login"})
     *
     * @SWG\Property(description="Last name of the user.")
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create"})
     *
     * @SWG\Property(description="Phone of the user.")
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create"})
     *
     * @SWG\Property(description="Born date-time of the user.")
     */
    private $bornedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @SWG\Property(description="Created date-time.")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @SWG\Property(description="Updated date-time.")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBornedAt(): ?\DateTimeInterface
    {
        return $this->bornedAt;
    }

    public function setBornedAt(\DateTimeInterface $bornedAt): self
    {
        $this->bornedAt = $bornedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
