<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnoncesRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annoncesRedigees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbViews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="annonces", orphanRemoval=true)
     * @var string
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="annonce")
     */
    private $messages;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }
    /**
     * Initialise les valeurs par défault lors de la création de l'annonce
     * @ORM\PrePersist
     */
    public function setDefaultValues()
    {
        $this->createdAt = new DateTime();
        $this->setNbViews(0);
    }
    public function __toString()
    {
       return $this->getAnnonceTitre();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNbViews(): ?int
    {
        return $this->nbViews;
    }

    public function setNbViews(?int $nbViews): self
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setAnnonces($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonces() === $this) {
                $image->setAnnonces(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAnnonce($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAnnonce() === $this) {
                $message->setAnnonce(null);
            }
        }

        return $this;
    }
}
