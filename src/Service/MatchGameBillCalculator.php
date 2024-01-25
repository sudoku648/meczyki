<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MatchGameBill;
use App\Model\MatchGameBillValues;
use App\Service\Contracts\MatchGameBillCalculatorInterface;

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
