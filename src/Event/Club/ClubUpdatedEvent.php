<?php

declare(strict_types=1);

namespace App\Event\Club;

use App\Entity\Club;

class ClubUpdatedEvent
{
    public function __construct(
        public readonly Club $club,
    ) {
    }
}
