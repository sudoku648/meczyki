<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;

class UserPageView extends Criteria
{
    public string $sortColumn = DoctrineUserRepository::SORT_DEFAULT;

    public string $sortDirection = DoctrineUserRepository::SORT_DIR_DEFAULT;

    public ?string $globalSearch = null;

    public ?string $usernameLike = null;
}
