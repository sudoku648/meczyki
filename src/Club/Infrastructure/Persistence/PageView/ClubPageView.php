<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\Club\Infrastructure\Persistence\Doctrine\DoctrineClubRepository;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

class ClubPageView extends Criteria
{
    public string $sortColumn = DoctrineClubRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineClubRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
