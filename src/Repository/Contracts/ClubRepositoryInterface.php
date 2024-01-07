<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\ClubPageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface ClubRepositoryInterface
{
    public function getTotalCount(): int;

    public function countByCriteria(ClubPageView|Criteria $criteria): int;

    public function findByCriteria(ClubPageView|Criteria $criteria): PagerfantaInterface;
}
