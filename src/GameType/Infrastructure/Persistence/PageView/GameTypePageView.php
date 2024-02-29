<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory\DataTableGameTypeCriteriaFactory;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

class GameTypePageView extends Criteria
{
    public string $sortColumn = DataTableGameTypeCriteriaFactory::SORT_DEFAULT;

    public string $sortDirection = DataTableGameTypeCriteriaFactory::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
