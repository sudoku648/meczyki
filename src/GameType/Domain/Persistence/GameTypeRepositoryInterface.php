<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView\GameTypePageView;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

interface GameTypeRepositoryInterface
{
    public function persist(GameType $gameType): void;

    public function remove(GameType $gameType): void;

    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(GameTypePageView|Criteria $criteria): int;

    public function findByCriteria(GameTypePageView|Criteria $criteria): PagerfantaInterface;
}
