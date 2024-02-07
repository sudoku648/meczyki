<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Service;

use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\MatchGameBillValues;

interface MatchGameBillCalculatorInterface
{
    public function calculate(MatchGameBill $matchGameBill): MatchGameBillValues;
}
