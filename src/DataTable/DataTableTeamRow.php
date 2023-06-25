<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTableTeamRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $name = '',
        public string $club = '',
        string $actions = ''
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
