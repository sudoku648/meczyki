<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Persistence;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Domain\ValueObject\PersonId;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;

interface PersonRepositoryInterface
{
    public function persist(Person $person): void;

    public function remove(Person $person): void;

    /**
     * @return Person[]
     */
    public function allOrderedByName(?MatchGameFunction $function = null): array;

    public function getTotalCount(): int;

    public function countByCriteria(PersonPageView $criteria): int;

    /**
     * @return Person[]
     */
    public function findByCriteria(PersonPageView $criteria): array;

    public function existsWithMobilePhoneAndId(string $mobilePhone, ?PersonId $personId): bool;
}
