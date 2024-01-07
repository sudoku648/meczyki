<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;
use App\Repository\DoctrinePersonRepository;

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
