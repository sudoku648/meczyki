<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

trait DataTableOrdinalNumberTrait
{
    private function getOrdinalNumber(int $key, Criteria $criteria): int
    {
        return $key + 1 + ($criteria->page - 1) * $criteria->perPage;
    }
}
