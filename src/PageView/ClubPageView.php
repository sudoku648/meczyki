<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\ClubRepository;
use App\Repository\Criteria;

class ClubPageView extends Criteria
{
    public string $sortColumn = ClubRepository::SORT_DEFAULT;

    public string $sortDirection = ClubRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
