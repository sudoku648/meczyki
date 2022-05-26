<?php

declare(strict_types=1);

namespace App\Event\MatchGame;

use App\Entity\MatchGame;

class MatchGameCreatedEvent
{
    private MatchGame $matchGame;

    public function __construct(MatchGame $matchGame)
    {
        $this->matchGame = $matchGame;
    }

    public function getMatchGame(): MatchGame
    {
        return $this->matchGame;
    }
}
