<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\UserRepository;

class UserPageView extends Criteria
{
    public string $sortColumn = UserRepository::SORT_DEFAULT;

    public string $sortDirection = UserRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $usernameLike = null;
}
