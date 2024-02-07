<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Service;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Sudoku648\Meczyki\User\Domain\Entity\User;

interface MatchGameManagerInterface
{
    public function create(MatchGameDto $dto, User $user): MatchGame;

    public function edit(MatchGame $matchGame, MatchGameDto $dto): MatchGame;

    public function delete(MatchGame $matchGame): void;

    public function createDto(MatchGame $matchGame): MatchGameDto;
}
