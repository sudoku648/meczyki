<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Factory;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamName;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamShortName;
use Sudoku648\Meczyki\Team\Frontend\Dto\TeamDto;

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
