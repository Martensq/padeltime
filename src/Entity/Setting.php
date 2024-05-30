<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom du club est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $clubName = null;

    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[ORM\Column]
    private ?float $peakHoursPrice = null;

    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[ORM\Column]
    private ?float $offPeakHoursPrice = null;

    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[ORM\Column]
    private ?float $racketRentalPrice = null;

    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[ORM\Column]
    private ?float $ballPrice = null;

    #[Assert\NotBlank(message: "L'horaire est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'horaire ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $weekOpeningHours = null;

    #[Assert\NotBlank(message: "L'horaire est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'horaire ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $weekEndOpeningHours = null;

    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'email ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le numéro de téléphone ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        "/^[0-9\s\-\(\)\+]{6,60}$/",
        message: "Le numéro de téléphone n'est pas valide."
    )]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[Assert\NotBlank(message: "L'adresse est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank(message: "Le lien Google Maps est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le lien Google Maps ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $mapLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le lien Linkedin ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedinLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le lien Facebook ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le lien Instagram ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagramLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le lien Tiktok ne doit pas dépasser {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tiktokLink = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClubName(): ?string
    {
        return $this->clubName;
    }

    public function setClubName(?string $clubName): static
    {
        $this->clubName = $clubName;

        return $this;
    }

    public function getPeakHoursPrice(): ?float
    {
        return $this->peakHoursPrice;
    }

    public function setPeakHoursPrice(?float $peakHoursPrice): static
    {
        $this->peakHoursPrice = $peakHoursPrice;

        return $this;
    }

    public function getOffPeakHoursPrice(): ?float
    {
        return $this->offPeakHoursPrice;
    }

    public function setOffPeakHoursPrice(?float $offPeakHoursPrice): static
    {
        $this->offPeakHoursPrice = $offPeakHoursPrice;

        return $this;
    }

    public function getRacketRentalPrice(): ?float
    {
        return $this->racketRentalPrice;
    }

    public function setRacketRentalPrice(?float $racketRentalPrice): static
    {
        $this->racketRentalPrice = $racketRentalPrice;

        return $this;
    }

    public function getBallPrice(): ?float
    {
        return $this->ballPrice;
    }

    public function setBallPrice(?float $ballPrice): static
    {
        $this->ballPrice = $ballPrice;

        return $this;
    }

    public function getWeekOpeningHours(): ?string
    {
        return $this->weekOpeningHours;
    }

    public function setWeekOpeningHours(?string $weekOpeningHours): static
    {
        $this->weekOpeningHours = $weekOpeningHours;

        return $this;
    }

    public function getWeekEndOpeningHours(): ?string
    {
        return $this->weekEndOpeningHours;
    }

    public function setWeekEndOpeningHours(?string $weekEndOpeningHours): static
    {
        $this->weekEndOpeningHours = $weekEndOpeningHours;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getMapLink(): ?string
    {
        return $this->mapLink;
    }

    public function setMapLink(?string $mapLink): static
    {
        $this->mapLink = $mapLink;

        return $this;
    }

    public function getLinkedinLink(): ?string
    {
        return $this->linkedinLink;
    }

    public function setLinkedinLink(?string $linkedinLink): static
    {
        $this->linkedinLink = $linkedinLink;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): static
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): static
    {
        $this->instagramLink = $instagramLink;

        return $this;
    }

    public function getTiktokLink(): ?string
    {
        return $this->tiktokLink;
    }

    public function setTiktokLink(?string $tiktokLink): static
    {
        $this->tiktokLink = $tiktokLink;

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
}
