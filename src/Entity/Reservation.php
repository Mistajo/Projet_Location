<?php

namespace App\Entity;



use DateTime;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Static_;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Agency $agency = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Vehicle $vehicle = null;

    #[Assert\NotBlank(message: "La date de début est obligatoire.")]
    #[Assert\GreaterThan('today UTC')]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $startDate = null;

    #[Assert\NotBlank(message: "La date de fin est obligatoire.")]
    #[Assert\GreaterThan('today UTC')]
    #[Assert\GreaterThan(
        propertyPath: "startDate",
        message: "La date de fin doit etre supperieur à la date de début"
    )]
    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalPrice = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\GreaterThan(0)]
    #[ORM\Column(nullable: true)]
    private ?float $dailyPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): static
    {
        $this->agency = $agency;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {

        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTotalPrice(): ?float
    {

        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function calculerPrixTotal()
    {
        $diff = $this->endDate->diff($this->startDate);
        $nbJours = $diff->days;

        return $this->dailyPrice * $nbJours;
    }


    public function getDailyPrice(): ?float
    {
        return $this->dailyPrice;
    }

    public function setDailyPrice(?float $dailyPrice): static
    {
        $this->dailyPrice = $dailyPrice;

        return $this;
    }
}
