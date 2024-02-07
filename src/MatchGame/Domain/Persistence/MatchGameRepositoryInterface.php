<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView\MatchGamePageView;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

interface MatchGameRepositoryInterface
{
    public function getTotalCount(): int;

    public function countByCriteria(MatchGamePageView|Criteria $criteria): int;

    public function findByCriteria(MatchGamePageView|Criteria $criteria): PagerfantaInterface;
}
