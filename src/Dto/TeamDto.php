<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDto
{
    private ?string $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 200)]
    public ?string $fullName = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $shortName = null;

    public ?Club $club = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
