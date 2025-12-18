<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 20)]
    private string $selector;

    #[ORM\Column(length: 100)]
    private string $hashedToken;

    #[ORM\Column]
    private \DateTimeImmutable $requestedAt;

    #[ORM\Column]
    private \DateTimeImmutable $expiresAt;

    public function __construct(User $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->expiresAt = \DateTimeImmutable::createFromInterface($expiresAt);
        $this->selector = $selector;
        $this->hashedToken = $hashedToken;
        $this->requestedAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int { return $this->id; }

    public function getUser(): User { return $this->user; }

    public function getRequestedAt(): \DateTimeInterface { return $this->requestedAt; }

    public function getExpiresAt(): \DateTimeInterface { return $this->expiresAt; }

    public function getHashedToken(): string { return $this->hashedToken; }

    public function getSelector(): string { return $this->selector; }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= time();
    }
}
