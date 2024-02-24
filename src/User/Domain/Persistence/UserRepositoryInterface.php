<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Domain\Persistence;

use Pagerfanta\PagerfantaInterface;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView\UserPageView;

interface UserRepositoryInterface
{
    public function persist(User $user): void;

    public function remove(User $user): void;

    public function getTotalCount(): int;

    public function countByCriteria(UserPageView|Criteria $criteria): int;

    public function findByCriteria(UserPageView|Criteria $criteria): PagerfantaInterface;
}
