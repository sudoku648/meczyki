<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Persistence;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView\GameTypePageView;

interface GameTypeRepositoryInterface
{
    public function persist(GameType $gameType): void;

    public function remove(GameType $gameType): void;

    /**
     * @return GameType[]
     */
    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(GameTypePageView $criteria): int;

    /**
     * @return GameType[]
     */
    public function findByCriteria(GameTypePageView $criteria): array;
}
