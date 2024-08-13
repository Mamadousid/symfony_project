<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $day = null;

    #[ORM\Column(length: 255)]
    private ?string $time_open = null;

    #[ORM\Column(length: 255)]
    private ?string $time_closed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getTimeOpen(): ?string
    {
        return $this->time_open;
    }

    public function setTimeOpen(string $time_open): static
    {
        $this->time_open = $time_open;

        return $this;
    }

    public function getTimeClosed(): ?string
    {
        return $this->time_closed;
    }

    public function setTimeClosed(string $time_closed): static
    {
        $this->time_closed = $time_closed;

        return $this;
    }
}
