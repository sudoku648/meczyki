<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\MatchGamePageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface MatchGameRepositoryInterface
{
    public function getTotalCount(): int;

    public function countByCriteria(MatchGamePageView|Criteria $criteria): int;

    public function findByCriteria(MatchGamePageView|Criteria $criteria): PagerfantaInterface;
}
