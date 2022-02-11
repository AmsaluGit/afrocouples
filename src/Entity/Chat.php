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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mfrom;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     */
    private $mto;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMto(): ?User
    {
        return $this->mto;
    }

    public function setMto(?User $mto): self
    {
        $this->mto = $mto;

        return $this;
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

   
}
