<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\User\Frontend\DataTable\Factory\DataTableUserCriteriaFactory;

class UserPageView extends Criteria
{
    public string $sortColumn = DataTableUserCriteriaFactory::SORT_DEFAULT;

    public string $sortDirection = DataTableUserCriteriaFactory::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $usernameLike = null;
}
