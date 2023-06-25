<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTablePersonRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $fullName = '',
        public string $functions = '',
        string $actions = ''
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
