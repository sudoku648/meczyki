<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\DataTable\Model;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableRow;

readonly class DataTableUserRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $username = '',
        string $actions = '',
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
