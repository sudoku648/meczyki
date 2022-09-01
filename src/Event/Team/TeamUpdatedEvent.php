<?php

declare(strict_types=1);

namespace App\Event\Team;

use App\Entity\Team;

class TeamUpdatedEvent
{
    public function __construct(
        private Team $team
    ) {
    }

    public function getTeam(): Team
    {
        return $this->team;
    }
}
