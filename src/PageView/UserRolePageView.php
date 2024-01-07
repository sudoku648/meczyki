<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrineUserRoleRepository;

class UserRolePageView extends Criteria
{
    public string $sortColumn = DoctrineUserRoleRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineUserRoleRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
