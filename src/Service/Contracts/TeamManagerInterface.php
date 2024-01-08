<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\TeamDto;
use App\Entity\Team;

interface TeamManagerInterface
{
    public function create(TeamDto $dto): Team;

    public function edit(Team $team, TeamDto $dto): Team;

    public function delete(Team $team): void;

    public function createDto(Team $team): TeamDto;
}
