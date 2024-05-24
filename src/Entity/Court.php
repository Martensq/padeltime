<?php

namespace App\Entity;

use App\Repository\CourtRepository;
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

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

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
