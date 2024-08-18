<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Service;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;

interface GameTypeManagerInterface
{
    public function create(GameTypeDto $dto): GameType;

    public function edit(GameType $gameType, GameTypeDto $dto): GameType;

    public function delete(GameType $gameType): void;

    public function detachImage(GameType $gameType): void;
}
