<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTableRow
{
    public function __construct(
        public int $ordinalNumber = 0,
        public string $checkbox = '',
        public string $actions = ''
    ) {
    }
}
