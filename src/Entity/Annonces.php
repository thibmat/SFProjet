<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 */
class Annonces
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $annonceTitre;

    /**
     * @ORM\Column(type="text")
     */
    private $annonceTexte;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(type="float")
     */
    private $annoncePrix;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="annonces")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonceTitre(): ?string
    {
        return $this->annonceTitre;
    }

    public function setAnnonceTitre(string $annonceTitre): self
    {
        $this->annonceTitre = $annonceTitre;

        return $this;
    }

    public function getAnnonceTexte(): ?string
    {
        return $this->annonceTexte;
    }

    public function setAnnonceTexte(string $annonceTexte): self
    {
        $this->annonceTexte = $annonceTexte;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getAnnoncePrix(): ?float
    {
        return $this->annoncePrix;
    }

    public function setAnnoncePrix(float $annoncePrix): self
    {
        $this->annoncePrix = $annoncePrix;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->userId->contains($userId)) {
            $this->userId[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->userId->contains($userId)) {
            $this->userId->removeElement($userId);
        }

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }
}
