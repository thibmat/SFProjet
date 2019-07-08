<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolesRepository")
 */
class Roles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $roleLibelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleLibelle(): ?string
    {
        return $this->roleLibelle;
    }

    public function setRoleLibelle(string $roleLibelle): self
    {
        $this->roleLibelle = $roleLibelle;

        return $this;
    }
}
