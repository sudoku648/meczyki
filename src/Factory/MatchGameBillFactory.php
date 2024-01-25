<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\MatchGameBillDto;
use App\Entity\MatchGameBill;
use App\Entity\Person;
use App\ValueObject\BaseEquivalentPercent;
use App\ValueObject\Money;
use App\ValueObject\TaxDeductibleStakePercent;
use App\ValueObject\TaxIncomeStakePercent;

class MatchGameBillFactory
{
    public function createFromDto(MatchGameBillDto $dto, Person $person): MatchGameBill
    {
        return new MatchGameBill(
            $person,
            $dto->matchGame,
            Money::PLN($dto->baseEquivalent),
            BaseEquivalentPercent::byValue($dto->percentOfBaseEquivalent),
            TaxDeductibleStakePercent::byValue($dto->taxDeductibleStakePercent),
            TaxIncomeStakePercent::byValue($dto->incomeTaxStakePercent),
        );
    }

    public function createDto(MatchGameBill $matchGameBill): MatchGameBillDto
    {
        $dto = new MatchGameBillDto();

        $dto->person                    = $matchGameBill->getPerson();
        $dto->matchGame                 = $matchGameBill->getMatchGame();
        $dto->baseEquivalent            = $matchGameBill->getBaseEquivalent()->getAmount();
        $dto->percentOfBaseEquivalent   = (int) $matchGameBill->getPercentOfBaseEquivalent()->getValue();
        $dto->grossEquivalent           = $matchGameBill->getGrossEquivalent()->getAmount();
        $dto->taxDeductibleStakePercent = (int) $matchGameBill->getTaxDeductibleStakePercent()->getValue();
        $dto->taxDeductibleExpenses     = $matchGameBill->getTaxDeductibleExpenses()->getAmount();
        $dto->taxationBase              = $matchGameBill->getTaxationBase()->getAmount();
        $dto->incomeTaxStakePercent     = (int) $matchGameBill->getIncomeTaxStakePercent()->getValue();
        $dto->incomeTax                 = $matchGameBill->getIncomeTax()->getAmount();
        $dto->equivalentToWithdraw      = $matchGameBill->getEquivalentToWithdraw()->getAmount();
        $dto->setId($matchGameBill->getId());

        return $dto;
    }
}
