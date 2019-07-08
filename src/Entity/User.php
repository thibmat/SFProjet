<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 * @UniqueEntity(fields={"userName"}, message="There is already an account with this userName")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $userName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userAdress;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userCodePostal;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $userVille;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $userTel;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Annonces", mappedBy="userId")
     */
    private $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }
    public function __toString():string
    {
        return $this->getUsername();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->createdAt = $CreatedAt;

        return $this;
    }

    public function getUserAdress(): ?string
    {
        return $this->userAdress;
    }

    public function setUserAdress(?string $userAdress): self
    {
        $this->userAdress = $userAdress;

        return $this;
    }

    public function getUserCodePostal(): ?int
    {
        return $this->userCodePostal;
    }

    public function setUserCodePostal(?int $userCodePostal): self
    {
        $this->userCodePostal = $userCodePostal;

        return $this;
    }

    public function getUserVille(): ?string
    {
        return $this->userVille;
    }

    public function setUserVille(?string $userVille): self
    {
        $this->userVille = $userVille;

        return $this;
    }

    public function getUserTel(): ?string
    {
        return $this->userTel;
    }

    public function setUserTel(?string $userTel): self
    {
        $this->userTel = $userTel;

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
            $annonce->addUserId($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            $annonce->removeUserId($this);
        }

        return $this;
    }
}
