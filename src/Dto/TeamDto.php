<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use App\ValueObject\TeamId;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDto
{
    private ?TeamId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 200)]
    public ?string $fullName = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $shortName = null;

    public ?Club $club = null;

    public function getId(): ?TeamId
    {
        return $this->id;
    }

    public function setId(TeamId $id): void
    {
        $this->id = $id;
    }
}
