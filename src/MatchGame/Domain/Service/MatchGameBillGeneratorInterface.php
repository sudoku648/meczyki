<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;

interface MatchGameBillGeneratorInterface
{
    public function generate(MatchGameBill $matchGameBill): Spreadsheet;
}
