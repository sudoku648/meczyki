<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\DataTable\Model;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableRow;

readonly class DataTableClubRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $name = '',
        string $actions = '',
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
