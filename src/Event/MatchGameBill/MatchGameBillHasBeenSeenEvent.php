<?php

declare(strict_types=1);

namespace App\Event\MatchGameBill;

use App\Entity\MatchGameBill;

class MatchGameBillHasBeenSeenEvent
{
    private MatchGameBill $matchGameBill;

    public function __construct(MatchGameBill $matchGameBill)
    {
        $this->matchGameBill = $matchGameBill;
    }

    public function getMatchGameBill(): MatchGameBill
    {
        return $this->matchGameBill;
    }
}
