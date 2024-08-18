<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;

interface MatchGameBillManagerInterface
{
    public function create(MatchGameBillDto $dto, Person $person): MatchGameBill;

    public function edit(MatchGameBill $matchGameBill, MatchGameBillDto $dto): MatchGameBill;

    public function delete(MatchGameBill $matchGameBill): void;

    public function generateXlsx(MatchGameBill $matchGameBill): Spreadsheet;
}
