<?php

declare(strict_types=1);

namespace App\Event\GameType;

use App\Entity\GameType;

class GameTypeUpdatedEvent
{
    public function __construct(
        private GameType $gameType
    ) {
    }

    public function getGameType(): GameType
    {
        return $this->gameType;
    }
}
