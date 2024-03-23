<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class WifiCode {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: 'string', unique: true)]
    #[Assert\NotBlank]
    private ?string $code;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    #[Assert\GreaterThan(0)]
    private ?int $duration;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $requestedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $requestedBy;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $comment;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(?string $code): WifiCode {
        $this->code = $code;
        return $this;
    }

    public function getDuration(): ?int {
        return $this->duration;
    }

    public function setDuration(?int $duration): WifiCode {
        $this->duration = $duration;
        return $this;
    }

    public function getRequestedAt(): ?DateTime {
        return $this->requestedAt;
    }

    public function setRequestedAt(?DateTime $requestedAt): WifiCode {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    public function getRequestedBy(): ?User {
        return $this->requestedBy;
    }

    public function setRequestedBy(?User $requestedBy): WifiCode {
        $this->requestedBy = $requestedBy;
        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }
    public function setComment(?string $comment): WifiCode {
        $this->comment = $comment;
        return $this;
    }
}