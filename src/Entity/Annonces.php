<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 * @ORM\HasLifecycleCallbacks()
 *
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
     * @Assert\Length(
     *     min = 4,
     *     max = 255
     * )
     */
    private $annonceTitre;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min = 25,
     *     max = 4000
     * )
     */
    private $annonceTexte;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     * @Assert\Type(type="numeric")
     * @Assert\Range(
     *     min=0,
     *     max=999999.99
     * )
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", inversedBy="annonces")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annoncesRedigees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
