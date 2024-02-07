<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Event;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;

readonly class GameTypeHasBeenSeenEvent
{
    public function __construct(
        public GameType $gameType,
    ) {
    }
}
