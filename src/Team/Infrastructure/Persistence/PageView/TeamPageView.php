<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Factory\DataTableTeamCriteriaFactory;

class TeamPageView extends Criteria
{
    public string $sortColumn = DataTableTeamCriteriaFactory::SORT_DEFAULT;

    public string $sortDirection = DataTableTeamCriteriaFactory::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?Club $club = null;

    public ?string $nameLike = null;

    public ?string $clubNameLike = null;
}
