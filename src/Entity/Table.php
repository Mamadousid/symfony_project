<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity(fields: ['name'], message: 'Cette table existe déjà. Veuillez choisir un autre type de table.')]
#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $disponibilité = null;

    #[Assert\Regex(
        pattern: "/^[0-9\-\+\s\(\)]{1,20}$/",
        match: true,
        message: "Le numéro de la table doit être un nombre",
    )]
    #[ORM\Column(length: 255)]
    private ?string $places = null;


    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'typetable', orphanRemoval: true)]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setTypetable($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getTypetable() === $this) {
                $booking->setTypetable(null);
            }
        }

        return $this;
    }

}
