<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\DataTable;

readonly class DataTableRow
{
    public function __construct(
        public int $ordinalNumber = 0,
        public string $checkbox = '',
        public string $actions = '',
    ) {
    }
}
