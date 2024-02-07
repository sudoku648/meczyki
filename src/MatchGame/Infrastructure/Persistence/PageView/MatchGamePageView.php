<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Doctrine\DoctrineMatchGameRepository;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

class MatchGamePageView extends Criteria
{
    public string $sortColumn = DoctrineMatchGameRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineMatchGameRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $dateTimeLike = null;

    public ?string $gameTypeLike = null;

    public ?string $teamsLike = null;
}
