<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTeacherRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class UserTeacher extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SchoolClass", mappedBy="teacher")
     *
     * @JMS\Expose()
     * @JMS\Groups({"teacher_item"})
     */
    private $schoolClasses;

    /**
     * UserTeacher constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->schoolClasses = new ArrayCollection();
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
            $schoolClass->setTeacher($this);
        }

        return $this;
    }

    public function removeSchoolClass(SchoolClass $schoolClass): self
    {
        if ($this->schoolClasses->contains($schoolClass)) {
            $this->schoolClasses->removeElement($schoolClass);
            // set the owning side to null (unless already changed)
            if ($schoolClass->getTeacher() === $this) {
                $schoolClass->setTeacher(null);
            }
        }

        return $this;
    }
}
