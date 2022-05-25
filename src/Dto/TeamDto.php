<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 200)]
    public ?string $fullName = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $shortName = null;

    public Club|ClubDto|null $club = null;

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
