<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Model;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableRow;

readonly class DataTableRefereeObserverRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $fullName = '',
        string $actions = '',
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
