<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrineGameTypeRepository;

class GameTypePageView extends Criteria
{
    public string $sortColumn = DoctrineGameTypeRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineGameTypeRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
