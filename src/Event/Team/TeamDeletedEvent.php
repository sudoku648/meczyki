<?php

declare(strict_types=1);

namespace App\Event\Team;

use App\Entity\Team;

class TeamDeletedEvent
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }
}
