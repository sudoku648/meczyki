<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\MatchGameDto;
use App\Entity\MatchGame;
use App\Entity\User;

interface MatchGameManagerInterface
{
    public function create(MatchGameDto $dto, User $user): MatchGame;

    public function edit(MatchGame $matchGame, MatchGameDto $dto): MatchGame;

    public function delete(MatchGame $matchGame): void;

    public function createDto(MatchGame $matchGame): MatchGameDto;
}
