<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Persistence;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView\MatchGamePageView;

interface MatchGameRepositoryInterface
{
    public function persist(MatchGame $matchGame): void;

    public function remove(MatchGame $matchGame): void;

    public function getTotalCount(): int;

    public function countByCriteria(MatchGamePageView $criteria): int;

    /**
     * @return MatchGame[]
     */
    public function findByCriteria(MatchGamePageView $criteria): array;
}
