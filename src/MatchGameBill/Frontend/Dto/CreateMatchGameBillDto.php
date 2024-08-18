<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Dto;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Validator\Constraints as MatchGameBillAssert;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;

final class CreateMatchGameBillDto
{
    public function __construct(
        public ?Person $person = null,
        public ?MatchGame $matchGame = null,
        #[MatchGameBillAssert\BaseEquivalentRequirements]
        public ?int $baseEquivalent = null,
        #[MatchGameBillAssert\PercentOfBaseEquivalentRequirements]
        public ?int $percentOfBaseEquivalent = null,
        #[MatchGameBillAssert\TaxDeductibleStakePercentRequirements]
        public ?int $taxDeductibleStakePercent = null,
        #[MatchGameBillAssert\IncomeTaxStakePercentRequirements]
        public ?int $incomeTaxStakePercent = null,
    ) {
    }
}
