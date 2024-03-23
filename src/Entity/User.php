<?php

namespace App\Entity;

use Stringable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['username'])]
class User implements UserInterface, Stringable {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: 'uuid')]
    private UuidInterface $idpId;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $firstname = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Email]
    #[Assert\NotBlank(allowNull: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: 'json')]
    private array $data = [];

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return UuidInterface|null
     */
    public function getIdpId(): ?UuidInterface {
        return $this->idpId;
    }

    public function setIdpId(UuidInterface $uuid): User {
        $this->idpId = $uuid;
        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstname(): ?string {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): User {
        $this->email = $email;
        return $this;
    }

    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array {
        return $this->roles;
    }

    public function getData(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function setData(string $key, $data): void {
        $this->data[$key] = $data;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string {
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function unserialize($data): void {

    }

    public function __serialize(): array {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername()
        ];
    }

    public function __unserialize(array $data): void {
        $this->id = $data['id'];
        $this->username = $data['username'];
    }

    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString(): string {
        return $this->getUserIdentifier();
    }
}