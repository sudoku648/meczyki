<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part;

final readonly class MatchGameData
{
    public function __construct(
        public string $date,
        public string $venue,
        public string $gameTypeName,
        public string $homeTeamName,
        public string $awayTeamName,
    ) {
    }
}
