<?php

declare(strict_types=1);

namespace App\Event\GameType;

use App\Entity\GameType;

readonly class GameTypeHasBeenSeenEvent
{
    public function __construct(
        public GameType $gameType,
    ) {
    }
}
