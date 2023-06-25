<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\UserRoleRepository;

class UserRolePageView extends Criteria
{
    public string $sortColumn = UserRoleRepository::SORT_DEFAULT;

    public string $sortDirection = UserRoleRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
