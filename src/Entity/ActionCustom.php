<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionCustomRepository")
 */
class ActionCustom extends Action
{

    /**
     * @ORM\ManyToOne(targetEntity="UserTeacher", inversedBy="actionCustoms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    public function getCreator(): ?UserTeacher
    {
        return $this->creator;
    }

    public function setCreator(?UserTeacher $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}
