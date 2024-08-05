<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(
        pattern: "/^[0-9\-\+\s\(\)]{1,20}$/",
        match: true,
        message: "Le numéro de téléphone n'est pas valide",
    )]
    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $disponibilité = null;

    #[Assert\Regex(
        pattern: "/^[0-9\-\+\s\(\)]{1,20}$/",
        match: true,
        message: "Le numéro de téléphone n'est pas valide",
    )]
    #[ORM\Column(length: 255)]
    private ?string $places = null;


    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getDiponibilité(): ?string
    {
        return $this->disponibilité;
    }

    public function setDiponibilité(string $diponibilité): static
    {
        $this->disponibilité = $diponibilité;

        return $this;
    }

    public function getPlaces(): ?string
    {
        return $this->places;
    }

    public function setPlaces(string $places): static
    {
        $this->places = $places;

        return $this;
    }

    public function getDisponibilité(): ?string
    {
        return $this->disponibilité;
    }

    public function setDisponibilité(?string $disponibilité): static
    {
        $this->disponibilité = $disponibilité;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

}
