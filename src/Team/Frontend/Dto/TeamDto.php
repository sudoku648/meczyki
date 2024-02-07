<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Dto;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamId;
use Symfony\Component\Validator\Constraints as Assert;

class TeamDto
{
    private ?TeamId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 200)]
    public ?string $name = null;

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
