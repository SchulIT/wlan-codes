<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['key'])]
class Setting {

    #[ORM\Id]
    #[ORM\Column(name: '`key`', type: 'string', unique: true)]
    #[Assert\NotBlank]
    private string $key;

    /**
     * @var mixed
     */
    #[ORM\Column(type: 'object')]
    private mixed $value = null;

    public function getKey(): string {
        return $this->key;
    }

    public function setKey(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function getValue(): mixed {
        return $this->value;
    }

    public function setValue(mixed $value): self {
        $this->value = $value;
        return $this;
    }
}