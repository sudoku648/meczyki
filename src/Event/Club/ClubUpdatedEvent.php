<?php

declare(strict_types=1);

namespace App\Event\Club;

use App\Entity\Club;

class ClubUpdatedEvent
{
    public function __construct(
        private Club $club
    ) {
    }

    public function getClub(): Club
    {
        return $this->club;
    }
}
