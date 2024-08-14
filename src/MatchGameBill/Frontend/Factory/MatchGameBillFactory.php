<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Factory;

use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\BaseEquivalentPercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxIncomeStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;

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
