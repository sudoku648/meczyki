<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

interface PersonRepositoryInterface
{
    public function allOrderedByName(?string $type = null): array;

    public function getTotalCount(): int;

    public function countByCriteria(PersonPageView|Criteria $criteria): int;

    public function findByCriteria(PersonPageView|Criteria $criteria): PagerfantaInterface;
}
