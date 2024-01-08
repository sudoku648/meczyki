<?php

declare(strict_types=1);

namespace App\Event\MatchGame;

use App\Entity\MatchGame;

readonly class MatchGameUpdatedEvent
{
    public function __construct(
        public MatchGame $matchGame,
    ) {
    }
}
