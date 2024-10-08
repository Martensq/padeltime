<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le prénom doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;


    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;


    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'email doit contenir au maximum {{ limit }} caractères.",
    )]
    #[Assert\Email(message: "L'email {{ value }} n'est pas valide.")]
    #[ORM\Column(length: 255)]
    private ?string $email = null;


    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le numéro de téléphone doit contenir au maximum {{ limit }} caractères.",
    )]
    #[Assert\Regex(
        "/^[0-9\s\-\(\)\+]{6,60}$/",
        message: "Le numéro de téléphone n'est pas valide."
    )]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[Assert\NotBlank(message: "Le message est obligatoire")]
    #[Assert\Length(
        max: 65535,
        maxMessage: "Le message doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

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

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
