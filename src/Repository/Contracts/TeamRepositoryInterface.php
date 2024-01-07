<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\TeamPageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface TeamRepositoryInterface
{
    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(TeamPageView|Criteria $criteria): int;

    public function findByCriteria(TeamPageView $criteria): PagerfantaInterface;
}
