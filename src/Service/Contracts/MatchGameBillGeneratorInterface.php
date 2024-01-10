<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Entity\MatchGameBill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface MatchGameBillGeneratorInterface
{
    public function generate(MatchGameBill $matchGameBill): Spreadsheet;
}
