<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\MatchGameBillDto;
use App\Entity\MatchGameBill;
use App\Entity\Person;

class MatchGameBillFactory
{
    public function createFromDto(MatchGameBillDto $dto, Person $person): MatchGameBill
    {
        return new MatchGameBill(
            $person,
            $dto->matchGame,
            $dto->baseEquivalent,
            $dto->percentOfBaseEquivalent,
            $dto->taxDeductibleStakePercent,
            $dto->incomeTaxStakePercent
        );
    }

    public function createDto(MatchGameBill $matchGameBill): MatchGameBillDto
    {
        $dto = new MatchGameBillDto();

        $dto->person                    = $matchGameBill->getPerson();
        $dto->matchGame                 = $matchGameBill->getMatchGame();
        $dto->baseEquivalent            = $matchGameBill->getBaseEquivalent();
        $dto->percentOfBaseEquivalent   = $matchGameBill->getPercentOfBaseEquivalent();
        $dto->grossEquivalent           = $matchGameBill->getGrossEquivalent();
        $dto->taxDeductibleStakePercent = $matchGameBill->getTaxDeductibleStakePercent();
        $dto->taxDeductibleExpenses     = $matchGameBill->getTaxDeductibleExpenses();
        $dto->taxationBase              = $matchGameBill->getTaxationBase();
        $dto->incomeTaxStakePercent     = $matchGameBill->getIncomeTaxStakePercent();
        $dto->incomeTax                 = $matchGameBill->getIncomeTax();
        $dto->equivalentToWithdraw      = $matchGameBill->getEquivalentToWithdraw();
        $dto->createdAt                 = $matchGameBill->getCreatedAt();
        $dto->createdAtAgo              = $matchGameBill->getCreatedAtAgo();
        $dto->updatedAt                 = $matchGameBill->getUpdatedAt();
        $dto->updatedAtAgo              = $matchGameBill->getUpdatedAtAgo();
        $dto->setId($matchGameBill->getId());

        return $dto;
    }
}
