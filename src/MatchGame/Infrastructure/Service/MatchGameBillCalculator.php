<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Service;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGame\Domain\Model\MatchGameBillValues;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameBillCalculatorInterface;

readonly class MatchGameBillCalculator implements MatchGameBillCalculatorInterface
{
    public function calculate(MatchGameBill $matchGameBill): MatchGameBillValues
    {
        $grossEquivalent       = $matchGameBill->getBaseEquivalent()->multiply($matchGameBill->getPercentOfBaseEquivalent()->getValue() / 100);
        $taxDeductibleExpenses = $grossEquivalent->multiply($matchGameBill->getTaxDeductibleStakePercent()->getValue() / 100);
        $taxationBase          = $grossEquivalent->subtract($taxDeductibleExpenses);
        $incomeTax             = $taxationBase->multiply($matchGameBill->getIncomeTaxStakePercent()->getValue() / 100);
        $equivalentToWithdraw  = $grossEquivalent->subtract($incomeTax);

        return new MatchGameBillValues(
            $grossEquivalent,
            $taxDeductibleExpenses,
            $taxationBase,
            $incomeTax,
            $equivalentToWithdraw,
        );
    }
}
