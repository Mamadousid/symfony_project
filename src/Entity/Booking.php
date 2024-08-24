<?php

namespace App\Entity;

use App\Entity\TimeMeal;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookingRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Validator\UniqueBooking;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[UniqueBooking]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de famille est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le nom de famille ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse e-mail est obligatoire.")]
    #[Assert\Email(message: "L'adresse e-mail '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date de réservation ne peut pas être une date passée."
    )]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_booking = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le choix des heures de repas est obligatoire.")]
    private ?TimeMeal $TimeMeal = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le choix des minutes de repas est obligatoire.")]
    private ?MinutesRepas $minutes = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre d'invités est obligatoire.")]
    #[Assert\GreaterThan(0, message: "Le nombre d'invités doit être supérieur à zéro.")]
    private ?int $guest = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le choix de la table est obligatoire.")]
    private ?Table $typetable = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le message est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "Le message ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $message = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'Le numéro de téléphone ne doit pas dépasser {{ limit }} caractères.',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9\-\+\s\(\)]{6,255}$/",
        match: true,
        message: "Le numéro de téléphone n'est pas valide",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[Assert\Callback]
    public function validateBookingTime(ExecutionContextInterface $context): void
    {
    $now = new \DateTimeImmutable();
    
    // Convertir les heures et minutes en entiers
    $heure = (int)$this->TimeMeal->getHeure();
    $minute = (int)$this->minutes->getMinute();
    
    // Créer un objet DateTimeImmutable avec la date de réservation et l'heure sélectionnée
    $reservationDateTime = $this->date_booking->setTime($heure, $minute);

    // Vérifier si la réservation est au moins 2 heures à l'avance
    if ($reservationDateTime <= $now->modify('+2 hours')) {
        $context->buildViolation('Vous devez réserver au moins 2 heures à l\'avance.')
            ->atPath('date_booking')
            ->addViolation();
    }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getDateBooking(): ?\DateTimeImmutable
    {
        return $this->date_booking;
    }

    public function setDateBooking(\DateTimeImmutable $date_booking): static
    {
        $this->date_booking = $date_booking;
        return $this;
    }

    public function getTimeMeal(): ?TimeMeal
    {
        return $this->TimeMeal;
    }

    public function setTimeMeal(?TimeMeal $TimeMeal): static
    {
        $this->TimeMeal = $TimeMeal;
        return $this;
    }

    public function getMinutes(): ?MinutesRepas
    {
        return $this->minutes;
    }

    public function setMinutes(?MinutesRepas $minutes): static
    {
        $this->minutes = $minutes;
        return $this;
    }

    public function getGuest(): ?int
    {
        return $this->guest;
    }

    public function setGuest(int $guest): static
    {
        $this->guest = $guest;
        return $this;
    }

    public function getTypetable(): ?Table
    {
        return $this->typetable;
    }

    public function setTypetable(?Table $typetable): static
    {
        $this->typetable = $typetable;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
