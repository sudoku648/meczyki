<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\DataTable;

readonly class DataTableParams
{
    public function __construct(
        public int $draw,
        public array $order,
        public array $searches,
        public int $length,
        public string $search,
        public int $page,
    ) {
    }
}
