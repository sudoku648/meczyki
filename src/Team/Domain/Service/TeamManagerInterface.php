<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Service;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Frontend\Dto\CreateTeamDto;
use Sudoku648\Meczyki\Team\Frontend\Dto\UpdateTeamDto;

interface TeamManagerInterface
{
    public function create(CreateTeamDto $dto): Team;

    public function edit(Team $team, UpdateTeamDto $dto): Team;

    public function delete(Team $team): void;
}
