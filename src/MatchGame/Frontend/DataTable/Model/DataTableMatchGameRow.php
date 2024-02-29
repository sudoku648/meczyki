<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Model;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableRow;

readonly class DataTableMatchGameRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $dateTime = '',
        public string $gameType = '',
        public string $teams = '',
        string $actions = '',
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
