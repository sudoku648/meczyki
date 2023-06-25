<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTableRefereeObserverRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $fullName = '',
        string $actions = ''
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
