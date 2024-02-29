<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Persistence;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;

interface PersonRepositoryInterface
{
    public function persist(Person $person): void;

    public function remove(Person $person): void;

    /**
     * @return Person[]
     */
    public function allOrderedByName(?string $type = null): array;

    public function getTotalCount(): int;

    public function countByCriteria(PersonPageView $criteria): int;

    /**
     * @return Person[]
     */
    public function findByCriteria(PersonPageView $criteria): array;
}
