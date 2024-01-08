<?php

declare(strict_types=1);

namespace App\Event\Club;

use App\Entity\Club;

readonly class ClubCreatedEvent
{
    public function __construct(
        public Club $club,
    ) {
    }
}
