<?php

namespace App\Entity;

use App\Repository\InterestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterestRepository::class)
 */
class Interest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="interests")
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageFrom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageTo;

    /**
     * @ORM\ManyToOne(targetEntity=Religion::class)
     */
    private $religion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\ManyToOne(targetEntity=Education::class)
     */
    private $education;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="interests")
     */
    private $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAgeFrom(): ?int
    {
        return $this->ageFrom;
    }

    public function setAgeFrom(?int $ageFrom): self
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    public function getAgeTo(): ?int
    {
        return $this->ageTo;
    }

    public function setAgeTo(?int $ageTo): self
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    public function getReligion(): ?Religion
    {
        return $this->religion;
    }

    public function setReligion(?Religion $religion): self
    {
        $this->religion = $religion;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getEducation(): ?Education
    {
        return $this->education;
    }

    public function setEducation(?Education $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
