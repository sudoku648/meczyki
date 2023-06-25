<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\GameTypeRepository;

class GameTypePageView extends Criteria
{
    public string $sortColumn = GameTypeRepository::SORT_DEFAULT;

    public string $sortDirection = GameTypeRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
