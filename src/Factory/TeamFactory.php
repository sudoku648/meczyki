<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\TeamDto;
use App\Entity\Team;
use App\ValueObject\TeamName;
use App\ValueObject\TeamShortName;

class TeamFactory
{
    public function createFromDto(TeamDto $dto): Team
    {
        return new Team(
            TeamName::fromString($dto->name),
            TeamShortName::fromString($dto->shortName),
            $dto->club,
        );
    }

    public function createDto(Team $team): TeamDto
    {
        $dto = new TeamDto();

        $dto->name      = $team->getName()->getValue();
        $dto->shortName = $team->getShortName()->getValue();
        $dto->club      = $team->getClub();
        $dto->setId($team->getId());

        return $dto;
    }
}
