<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserParentRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class UserParent extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Children", mappedBy="parent", orphanRemoval=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create", "parent_list"})
     */
    private $childrens;

    /**
     * UserParent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->childrens = new ArrayCollection();
    }

    /**
     * @return Collection|Children[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function getChildren(string $id)
    {
        foreach ($this->childrens as $item) {
            if ($item->getId() == $id) {
                return $item;
            }
        }
        return null;
    }

    public function addChildren(Children $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setParent($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            // set the owning side to null (unless already changed)
            if ($children->getParent() === $this) {
                $children->setParent(null);
            }
        }

        return $this;
    }
}
