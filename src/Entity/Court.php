<?php

namespace App\Entity;

use App\Repository\CourtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourtRepository::class)]
class Court
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le numéro de la piste est obligatoire")]
    #[Assert\Length(
        max: 2,
        maxMessage: "Le numéro ne doit pas dépasser {{ limit }} chiffres.",
    )]
    #[Assert\Regex(
        pattern: "/[1-9]+/",
        match: true,
        message: 'Seuls les chiffres supérieurs à 0 sont autorisés.',
    )]
    #[ORM\Column]
    private ?int $courtNumber = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $unavailableFrom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $unavailableTo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public $start;
    public $end;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'court')]
    private Collection $bookings;


    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourtNumber(): ?int
    {
        return $this->courtNumber;
    }

    public function setCourtNumber(?int $courtNumber): ?static
    {
        $this->courtNumber = $courtNumber;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUnavailableFrom(): ?\DateTimeInterface
    {
        return $this->unavailableFrom;
    }

    public function setUnavailableFrom(?\DateTimeInterface $unavailableFrom): static
    {
        $this->unavailableFrom = $unavailableFrom;

        return $this;
    }

    public function getUnavailableTo(): ?\DateTimeInterface
    {
        return $this->unavailableTo;
    }

    public function setUnavailableTo(?\DateTimeInterface $unavailableTo): static
    {
        $this->unavailableTo = $unavailableTo;

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
            $booking->setCourt($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getCourt() === $this) {
                $booking->setCourt(null);
            }
        }

        return $this;
    }
}
