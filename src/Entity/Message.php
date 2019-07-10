<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message
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
    private $messageTitre;

    /**
     * @ORM\Column(type="text")
     */
    private $messageTexte;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRead;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonces", inversedBy="messages")
     */
    private $annonce;

    /**
     * @ORM\Column(type="integer")
     */
    private $destinataire;

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function setDefaultValues()
    {
        $this->setCreatedAt(new DateTime());
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageTitre(): ?string
    {
        return $this->messageTitre;
    }

    public function setMessageTitre(string $messageTitre): self
    {
        $this->messageTitre = $messageTitre;

        return $this;
    }

    public function getMessageTexte(): ?string
    {
        return $this->messageTexte;
    }

    public function setMessageTexte(string $messageTexte): self
    {
        $this->messageTexte = $messageTexte;

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

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getAnnonce(): ?Annonces
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonces $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getDestinataire(): ?int
    {
        return $this->destinataire;
    }

    public function setDestinataire(int $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
