<?php

declare(strict_types=1);

namespace App\Event\MatchGame;

use App\Entity\MatchGame;

class MatchGameDeletedEvent
{
    public function __construct(
        public readonly MatchGame $matchGame,
    ) {
    }
}
