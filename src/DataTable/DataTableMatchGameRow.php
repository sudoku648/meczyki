<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTableMatchGameRow extends DataTableRow
{
    public function __construct(
        int $ordinalNumber = 1,
        string $checkbox = '',
        public string $dateTime = '',
        public string $gameType = '',
        public string $teams = '',
        string $actions = ''
    ) {
        parent::__construct($ordinalNumber, $checkbox, $actions);
    }
}
