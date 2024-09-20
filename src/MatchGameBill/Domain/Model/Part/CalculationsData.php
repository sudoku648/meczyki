<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part;

final readonly class CalculationsData
{
    public function __construct(
        public int $baseEquivalent,
        public float $percentOfBaseEquivalent,
        public int $grossEquivalent,
        public int $taxDeductibleExpenses,
        public int $taxationBase,
        public int $incomeTax,
        public int $equivalentToWithdraw,
        public string $amountInWords,
    ) {
    }
}
