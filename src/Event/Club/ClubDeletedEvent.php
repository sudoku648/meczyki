<?php

declare(strict_types=1);

namespace App\Event\Club;

use App\Entity\Club;

class ClubDeletedEvent
{
    private Club $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function getClub(): Club
    {
        return $this->club;
    }
}
