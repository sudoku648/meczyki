<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Model;

use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\CalculationsData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\MatchGameData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\PersonData;

final readonly class MatchGameBillXlsxData
{
    public function __construct(
        public MatchGameData $matchGame,
        public PersonData $person,
        public CalculationsData $calculations,
        public string $iban,
        public string $email,
        public bool $allowsToSendPitByEmail,
    ) {
    }
}
