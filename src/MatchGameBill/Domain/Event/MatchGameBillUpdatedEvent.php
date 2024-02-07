<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Event;

use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;

readonly class MatchGameBillUpdatedEvent
{
    public function __construct(
        public MatchGameBill $matchGameBill,
    ) {
    }
}
