<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Persistence;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;

interface MatchGameBillRepositoryInterface
{
    public function persist(MatchGameBill $matchGameBill): void;

    public function remove(MatchGameBill $matchGameBill): void;
}
