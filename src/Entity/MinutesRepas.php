<?php

namespace App\Entity;

use App\Repository\MinutesRepasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MinutesRepasRepository::class)]
class MinutesRepas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $minute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinute(): ?string
    {
        return $this->minute;
    }

    public function setMinute(string $minute): static
    {
        $this->minute = $minute;

        return $this;
    }
}
