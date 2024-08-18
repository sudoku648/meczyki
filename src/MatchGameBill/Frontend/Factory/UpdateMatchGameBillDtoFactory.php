<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Factory;

use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\UpdateMatchGameBillDto;

class UpdateMatchGameBillDtoFactory
{
    public static function fromEntity(MatchGameBill $matchGameBill): UpdateMatchGameBillDto
    {
        return new UpdateMatchGameBillDto(
            $matchGameBill->getId(),
            $matchGameBill->getPerson(),
            $matchGameBill->getMatchGame(),
            $matchGameBill->getBaseEquivalent()->getAmount(),
            (int) $matchGameBill->getPercentOfBaseEquivalent()->getValue(),
            (int) $matchGameBill->getTaxDeductibleStakePercent()->getValue(),
            (int) $matchGameBill->getIncomeTaxStakePercent()->getValue(),
        );
    }
}
