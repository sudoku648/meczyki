<?php

declare(strict_types=1);

namespace App\Event\Team;

use App\Entity\Team;

readonly class TeamHasBeenSeenEvent
{
    public function __construct(
        public Team $team,
    ) {
    }
}
