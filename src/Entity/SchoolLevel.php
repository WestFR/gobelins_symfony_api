<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolLevelRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class SchoolLevel
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     *
     * @JMS\Expose
     * @JMS\Groups({"school_name", "school_all"})
     *
     * @SWG\Property(description="School name level.")
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SchoolClass", mappedBy="schoolLevel")
     *
     * @JMS\Expose
     * @JMS\Groups({"school_all"})
     *
     * @SWG\Property(description="School classes for school level.")
     */
    private $schoolClasses;

    public function __construct()
    {
        $this->schoolClasses = new ArrayCollection();
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }


    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|SchoolClass[]
     */
    public function getSchoolClasses(): Collection
    {
        return $this->schoolClasses;
    }

    public function addSchoolClass(SchoolClass $schoolClass): self
    {
        if (!$this->schoolClasses->contains($schoolClass)) {
            $this->schoolClasses[] = $schoolClass;
            $schoolClass->setSchoolLevel($this);
        }

        return $this;
    }

    public function removeSchoolClass(SchoolClass $schoolClass): self
    {
        if ($this->schoolClasses->contains($schoolClass)) {
            $this->schoolClasses->removeElement($schoolClass);
            // set the owning side to null (unless already changed)
            if ($schoolClass->getSchoolLevel() === $this) {
                $schoolClass->setSchoolLevel(null);
            }
        }

        return $this;
    }

    // Update Method
    public function update(SchoolLevel $schoolLevel) {

        if($schoolLevel->getLabel() != null) {
            $this->label = $schoolLevel->getLabel();
        }

        /*if($schoolLevel->getSchoolClasses() != null) {
            $this->schoolClasses = $schoolLevel->getSchoolClasses();
        }*/

    }
}
