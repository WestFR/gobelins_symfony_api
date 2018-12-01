<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolClassRepository")
 */
class SchoolClass
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4)
     * @Assert\NotBlank()
     */
    private $yearStart;

    /**
     * @ORM\Column(type="string", length=4)
     * @Assert\NotBlank()
     * @Assert\Length(min="4", max="4")
     */
    private $yearEnd;

    /**

     * @ORM\OneToMany(targetEntity="App\Entity\Children", mappedBy="schoolClass")
     */
    private $childrens;

    /**

     * @ORM\ManyToOne(targetEntity="UserTeacher", inversedBy="schoolClasses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolLevel", inversedBy="schoolClasses")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="label")
     */
    private $schoolLevel;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearStart(): ?string
    {
        return $this->yearStart;
    }

    public function setYearStart(string $yearStart): self
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    public function getYearEnd(): ?string
    {
        return $this->yearEnd;
    }

    public function setYearEnd(string $yearEnd): self
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    public function getSchoolLevel(): ?SchoolLevel
    {
        return $this->schoolLevel;
    }

    public function setSchoolLevel(?SchoolLevel $schoolLevel): self
    {
        $this->schoolLevel = $schoolLevel;

        return $this;
    }

    /**
     * @return Collection|Children[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(Children $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setSchoolClass($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            // set the owning side to null (unless already changed)
            if ($children->getSchoolClass() === $this) {
                $children->setSchoolClass(null);
            }
        }

        return $this;
    }

    public function getTeacher(): ?UserTeacher
    {
        return $this->teacher;
    }

    public function setTeacher(?UserTeacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }
}
