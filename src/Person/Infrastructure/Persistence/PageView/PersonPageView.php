<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\Person\Infrastructure\Persistence\Doctrine\DoctrinePersonRepository;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

class PersonPageView extends Criteria
{
    public string $sortColumn = DoctrinePersonRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrinePersonRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public bool $isDelegate = false;

    public bool $isReferee = false;

    public bool $isRefereeObserver = false;

    public ?string $fullNameLike = null;
}
