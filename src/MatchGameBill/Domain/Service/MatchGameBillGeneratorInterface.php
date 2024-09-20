<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\MatchGameBillXlsxData;

interface MatchGameBillGeneratorInterface
{
    public function generate(MatchGameBillXlsxData $data): Spreadsheet;
}
