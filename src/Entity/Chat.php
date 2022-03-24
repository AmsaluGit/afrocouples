<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $seen=false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mto;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mfrom")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mfrom;
 
    

    public function getId(): ?int
    {
        return $this->id;
    }
 

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    public function __toString()
    {
        return "Chat details";
    }

    public function getMto(): ?User
    {
        return $this->mto;
    }

    public function setMto(?User $mto): self
    {
        $this->mto = $mto;

        return $this;
    }

    public function getMfrom(): ?User
    {
        return $this->mfrom;
    }

    public function setMfrom(?User $mfrom): self
    {
        $this->mfrom = $mfrom;

        return $this;
    }

   
   
}
