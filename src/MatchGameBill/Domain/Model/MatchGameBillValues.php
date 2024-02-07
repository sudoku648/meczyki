<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Model;

use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;

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
