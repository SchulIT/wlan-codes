<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class WifiCode {

    use IdTrait;
    use UuidTrait;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     * @var int|null
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime|null
     */
    private $requestedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     * @var User|null
     */
    private $requestedBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $comment;

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return WifiCode
     */
    public function setCode(?string $code): WifiCode {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     * @return WifiCode
     */
    public function setDuration(?int $duration): WifiCode {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getRequestedAt(): ?DateTime {
        return $this->requestedAt;
    }

    /**
     * @param DateTime|null $requestedAt
     * @return WifiCode
     */
    public function setRequestedAt(?DateTime $requestedAt): WifiCode {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getRequestedBy(): ?User {
        return $this->requestedBy;
    }

    /**
     * @param User|null $requestedBy
     * @return WifiCode
     */
    public function setRequestedBy(?User $requestedBy): WifiCode {
        $this->requestedBy = $requestedBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return WifiCode
     */
    public function setComment(?string $comment): WifiCode {
        $this->comment = $comment;
        return $this;
    }
}