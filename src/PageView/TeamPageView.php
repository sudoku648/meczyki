<?php

declare(strict_types=1);

namespace App\PageView;

use App\Entity\Club;
use App\Repository\Criteria;
use App\Repository\DoctrineTeamRepository;

class TeamPageView extends Criteria
{
    public string $sortColumn = DoctrineTeamRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineTeamRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?Club $club = null;

    public ?string $nameLike = null;

    public ?string $clubNameLike = null;
}
