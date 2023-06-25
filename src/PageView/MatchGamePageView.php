<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\MatchGameRepository;

class MatchGamePageView extends Criteria
{
    public string $sortColumn = MatchGameRepository::SORT_DEFAULT;

    public string $sortDirection = MatchGameRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $dateTimeLike = null;

    public ?string $gameTypeLike = null;

    public ?string $teamsLike = null;
}
