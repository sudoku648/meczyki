<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\PersonPageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface PersonRepositoryInterface
{
    public function allOrderedByName(?string $type = null): array;

    public function getTotalCount(): int;

    public function countByCriteria(PersonPageView|Criteria $criteria): int;

    public function findByCriteria(PersonPageView|Criteria $criteria): PagerfantaInterface;
}
