<?php

declare(strict_types=1);

namespace App\PageView;

use App\Entity\Club;
use App\Repository\Criteria;
use App\Repository\TeamRepository;

class TeamPageView extends Criteria
{
    public string $sortColumn = TeamRepository::SORT_DEFAULT;

    public string $sortDirection = TeamRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?Club $club = null;

    public ?string $nameLike = null;

    public ?string $clubNameLike = null;
}
