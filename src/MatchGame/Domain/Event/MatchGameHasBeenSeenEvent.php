<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Event;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;

readonly class MatchGameHasBeenSeenEvent
{
    public function __construct(
        public MatchGame $matchGame,
    ) {
    }
}
