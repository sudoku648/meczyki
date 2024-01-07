<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrineUserRepository;

class UserPageView extends Criteria
{
    public string $sortColumn = DoctrineUserRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineUserRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $usernameLike = null;
}
