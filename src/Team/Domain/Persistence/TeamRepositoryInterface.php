<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Persistence;

use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Infrastructure\Persistence\PageView\TeamPageView;

interface TeamRepositoryInterface
{
    public function persist(Team $team): void;

    public function remove(Team $team): void;

    /**
     * @return Team[]
     */
    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(TeamPageView $criteria): int;

    /**
     * @return Team[]
     */
    public function findByCriteria(TeamPageView $criteria): array;
}
