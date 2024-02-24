<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Infrastructure\Persistence\PageView\ClubPageView;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

interface ClubRepositoryInterface
{
    public function persist(Club $club): void;

    public function remove(Club $club): void;

    public function getTotalCount(): int;

    public function countByCriteria(ClubPageView|Criteria $criteria): int;

    public function findByCriteria(ClubPageView|Criteria $criteria): PagerfantaInterface;
}
