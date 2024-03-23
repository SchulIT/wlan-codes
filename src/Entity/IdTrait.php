<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id;

    public function getId(): ?int {
        return $this->id;
    }
}