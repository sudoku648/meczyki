<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Domain\Event;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;

readonly class ClubUpdatedEvent
{
    public function __construct(
        public Club $club,
    ) {
    }
}
