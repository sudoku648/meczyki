<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Entity\MatchGameBill;
use App\Model\MatchGameBillValues;

interface MatchGameBillCalculatorInterface
{
    public function calculate(MatchGameBill $matchGameBill): MatchGameBillValues;
}
