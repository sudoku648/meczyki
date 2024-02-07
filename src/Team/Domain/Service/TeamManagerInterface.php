<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Service;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Frontend\Dto\TeamDto;

interface TeamManagerInterface
{
    public function create(TeamDto $dto): Team;

    public function edit(Team $team, TeamDto $dto): Team;

    public function delete(Team $team): void;

    public function createDto(Team $team): TeamDto;
}
