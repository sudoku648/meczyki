<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\UserRolePageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface UserRoleRepositoryInterface
{
    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(UserRolePageView|Criteria $criteria): int;

    public function findByCriteria(UserRolePageView|Criteria $criteria): PagerfantaInterface;
}
