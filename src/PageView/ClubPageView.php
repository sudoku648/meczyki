<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrineClubRepository;

class ClubPageView extends Criteria
{
    public string $sortColumn = DoctrineClubRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineClubRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
