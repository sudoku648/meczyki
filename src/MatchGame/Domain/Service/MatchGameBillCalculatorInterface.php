<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Service;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGame\Domain\Model\MatchGameBillValues;

interface MatchGameBillCalculatorInterface
{
    public function calculate(MatchGameBill $matchGameBill): MatchGameBillValues;
}
