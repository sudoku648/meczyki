<?php

declare(strict_types=1);

namespace App\Event\GameType;

use App\Entity\GameType;

class GameTypeUpdatedEvent
{
    private GameType $gameType;

    public function __construct(GameType $gameType)
    {
        $this->gameType = $gameType;
    }

    public function getGameType(): GameType
    {
        return $this->gameType;
    }
}
