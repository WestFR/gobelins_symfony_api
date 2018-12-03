<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChildrenRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Children
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @JMS\Expose()
     * @JMS\Groups({"class_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose()
     * @JMS\Groups({"class_item"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose()
     * @JMS\Groups({"class_item"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     *
     * @JMS\Expose()
     * @JMS\Groups({"class_item"})
     */
    private $bornedAt;

    /**
     * @ORM\ManyToOne(targetEntity="UserParent", inversedBy="childrens")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="childrens")
     * @ORM\JoinColumn(nullable=false)
     *
     * @JMS\Expose()
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolClass", inversedBy="childrens")
     *
     * @JMS\Expose()
     */
    private $schoolClass;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Action", inversedBy="childrens")
     *
     * @JMS\Expose()
     */
    private $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBornedAt(): ?\DateTimeInterface
    {
        return $this->bornedAt;
    }

    public function setBornedAt(\DateTimeInterface $bornedAt): self
    {
        $this->bornedAt = $bornedAt;

        return $this;
    }

    public function getParent(): ?UserParent
    {
        return $this->parent;
    }

    public function setParent(?UserParent $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getSchoolClass(): ?SchoolClass
    {
        return $this->schoolClass;
    }

    public function setSchoolClass(?SchoolClass $schoolClass): self
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->contains($action)) {
            $this->actions->removeElement($action);
        }

        return $this;
    }
}
