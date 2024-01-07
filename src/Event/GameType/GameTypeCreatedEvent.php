<?php

declare(strict_types=1);

namespace App\Event\GameType;

use App\Entity\GameType;

class GameTypeCreatedEvent
{
    public function __construct(
        public readonly GameType $gameType,
    ) {
    }
}
