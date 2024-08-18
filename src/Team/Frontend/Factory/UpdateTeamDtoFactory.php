<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Factory;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Frontend\Dto\UpdateTeamDto;

class UpdateTeamDtoFactory
{
    public static function fromEntity(Team $team): UpdateTeamDto
    {
        return new UpdateTeamDto(
            $team->getId(),
            $team->getName()->getValue(),
            $team->getShortName()->getValue(),
            $team->getClub(),
        );
    }
}
