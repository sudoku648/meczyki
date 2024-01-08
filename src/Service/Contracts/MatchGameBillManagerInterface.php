<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\MatchGameBillDto;
use App\Entity\MatchGameBill;
use App\Entity\Person;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface MatchGameBillManagerInterface
{
    public function create(MatchGameBillDto $dto, Person $person): MatchGameBill;

    public function edit(MatchGameBill $matchGameBill, MatchGameBillDto $dto): MatchGameBill;

    public function delete(MatchGameBill $matchGameBill): void;

    public function generateXlsx(MatchGameBill $matchGameBill): Spreadsheet;

    public function createDto(MatchGameBill $matchGameBill): MatchGameBillDto;
}
