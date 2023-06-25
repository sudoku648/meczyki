<?php

declare(strict_types=1);

namespace App\DataTable;

readonly class DataTable
{
    public function __construct(
        public int $draw = 1,
        public int $recordsTotal = 0,
        public int $recordsFiltered = 0,
        public array $data = []
    ) {
    }
}
