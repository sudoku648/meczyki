<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Event;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;

readonly class TeamCreatedEvent
{
    public function __construct(
        public Team $team,
    ) {
    }
}
