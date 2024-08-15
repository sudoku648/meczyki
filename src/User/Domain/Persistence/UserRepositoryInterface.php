<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Domain\Persistence;

use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\ValueObject\UserId;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView\UserPageView;

interface UserRepositoryInterface
{
    public function persist(User $user): void;

    public function remove(User $user): void;

    public function getTotalCount(): int;

    public function countByCriteria(UserPageView $criteria): int;

    /**
     * @return User[]
     */
    public function findByCriteria(UserPageView $criteria): array;

    public function existsWithUsernameAndId(Username $username, ?UserId $userId): bool;
}
