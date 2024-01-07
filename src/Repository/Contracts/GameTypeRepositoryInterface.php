<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\PageView\GameTypePageView;
use App\Repository\Criteria;
use Pagerfanta\PagerfantaInterface;

interface GameTypeRepositoryInterface
{
    public function allOrderedByName(): array;

    public function getTotalCount(): int;

    public function countByCriteria(GameTypePageView|Criteria $criteria): int;

    public function findByCriteria(GameTypePageView|Criteria $criteria): PagerfantaInterface;
}
