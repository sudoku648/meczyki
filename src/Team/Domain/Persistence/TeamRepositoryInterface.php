<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Infrastructure\Persistence\PageView\TeamPageView;

interface TeamRepositoryInterface
{
    public function persist(Team $team): void;

    public function remove(Team $team): void;

    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(TeamPageView|Criteria $criteria): int;

    public function findByCriteria(TeamPageView $criteria): PagerfantaInterface;
}
