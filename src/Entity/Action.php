<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"action_custom" = "ActionCustom", "action" = "Action"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Action
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @JMS\Expose
     * @JMS\Groups({"action_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"action_list",  "parent_list"})
     */
    private $label;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"action_list",  "parent_list"})
     */
    private $score;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Children", mappedBy="actions")
     */
    private $childrens;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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
            $children->addAction($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            $children->removeAction($this);
        }

        return $this;
    }
}
