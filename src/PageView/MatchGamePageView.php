<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrineMatchGameRepository;

class MatchGamePageView extends Criteria
{
    public string $sortColumn = DoctrineMatchGameRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineMatchGameRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $dateTimeLike = null;

    public ?string $gameTypeLike = null;

    public ?string $teamsLike = null;
}
