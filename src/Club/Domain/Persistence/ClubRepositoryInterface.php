<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Domain\Persistence;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubId;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Infrastructure\Persistence\PageView\ClubPageView;

interface ClubRepositoryInterface
{
    public function persist(Club $club): void;

    public function remove(Club $club): void;

    public function getTotalCount(): int;

    public function countByCriteria(ClubPageView $criteria): int;

    /**
     * @return Club[]
     */
    public function findByCriteria(ClubPageView $criteria): array;

    public function existsWithNameAndId(ClubName $name, ?ClubId $clubId): bool;
}
