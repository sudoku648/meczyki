<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Doctrine\DoctrineGameTypeRepository;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

class GameTypePageView extends Criteria
{
    public string $sortColumn = DoctrineGameTypeRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineGameTypeRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $nameLike = null;
}
