<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\UserPageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface UserRepositoryInterface
{
    public function getTotalCount(): int;

    public function countByCriteria(UserPageView|Criteria $criteria): int;

    public function findByCriteria(UserPageView|Criteria $criteria): PagerfantaInterface;
}
