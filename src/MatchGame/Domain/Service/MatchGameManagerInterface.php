<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Service;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\CreateMatchGameDto;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\UpdateMatchGameDto;
use Sudoku648\Meczyki\User\Domain\Entity\User;

interface MatchGameManagerInterface
{
    public function create(CreateMatchGameDto $dto, User $user): MatchGame;

    public function edit(MatchGame $matchGame, UpdateMatchGameDto $dto): MatchGame;

    public function delete(MatchGame $matchGame): void;
}
