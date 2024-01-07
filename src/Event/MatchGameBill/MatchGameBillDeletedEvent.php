<?php

declare(strict_types=1);

namespace App\Event\MatchGameBill;

use App\Entity\MatchGameBill;

class MatchGameBillDeletedEvent
{
    public function __construct(
        public readonly MatchGameBill $matchGameBill,
    ) {
    }
}
