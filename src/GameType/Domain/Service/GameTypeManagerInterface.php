<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Service;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Frontend\Dto\CreateGameTypeDto;
use Sudoku648\Meczyki\GameType\Frontend\Dto\UpdateGameTypeDto;

interface GameTypeManagerInterface
{
    public function create(CreateGameTypeDto $dto): GameType;

    public function edit(GameType $gameType, UpdateGameTypeDto $dto): GameType;

    public function delete(GameType $gameType): void;

    public function detachImage(GameType $gameType): void;
}
