<?php

declare(strict_types=1);

namespace App\Event\MatchGameBill;

use App\Entity\MatchGameBill;

readonly class MatchGameBillUpdatedEvent
{
    public function __construct(
        public MatchGameBill $matchGameBill,
    ) {
    }
}
