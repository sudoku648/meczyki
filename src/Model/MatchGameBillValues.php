<?php

declare(strict_types=1);

namespace App\Model;

use App\ValueObject\Money;

readonly class MatchGameBillValues
{
    public function __construct(
        public Money $grossEquivalent,
        public Money $taxDeductibleExpenses,
        public Money $taxationBase,
        public Money $incomeTax,
        public Money $equivalentToWithdraw,
    ) {
    }
}
