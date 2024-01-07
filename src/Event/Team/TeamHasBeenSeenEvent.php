<?php

declare(strict_types=1);

namespace App\Event\Team;

use App\Entity\Team;

class TeamHasBeenSeenEvent
{
    public function __construct(
        public readonly Team $team,
    ) {
    }
}
