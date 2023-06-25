<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTableUserRoleRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $name = '',
        string $actions = ''
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
