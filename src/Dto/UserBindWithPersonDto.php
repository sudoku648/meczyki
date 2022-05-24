<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Person;

class UserBindWithPersonDto
{
    public ?Person $person = null;

    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
