<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $categoryLibelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonces", mappedBy="category")
     */
    private $annonces;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories", inversedBy="categorieEnfant")
     */
    private $categorieMere;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categories", mappedBy="categorieMere")
     */
    private $categorieEnfant;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->categorieEnfant = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getCategoryLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryLibelle(): ?string
    {
        return $this->categoryLibelle;
    }

    public function setCategoryLibelle(string $categoryLibelle): self
    {
        $this->categoryLibelle = $categoryLibelle;

        return $this;
    }

    /**
     * @return Collection|Annonces[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setCategory($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getCategory() === $this) {
                $annonce->setCategory(null);
            }
        }

        return $this;
    }

    public function getCategorieMere(): ?self
    {
        return $this->categorieMere;
    }

    public function setCategorieMere(?self $categorieMere): self
    {
        $this->categorieMere = $categorieMere;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategorieEnfant(): Collection
    {
        return $this->categorieEnfant;
    }

    public function addCategorieEnfant(self $categorieEnfant): self
    {
        if (!$this->categorieEnfant->contains($categorieEnfant)) {
            $this->categorieEnfant[] = $categorieEnfant;
            $categorieEnfant->setCategorieMere($this);
        }

        return $this;
    }

    public function removeCategorieEnfant(self $categorieEnfant): self
    {
        if ($this->categorieEnfant->contains($categorieEnfant)) {
            $this->categorieEnfant->removeElement($categorieEnfant);
            // set the owning side to null (unless already changed)
            if ($categorieEnfant->getCategorieMere() === $this) {
                $categorieEnfant->setCategorieMere(null);
            }
        }

        return $this;
    }
}
