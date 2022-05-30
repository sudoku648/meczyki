<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;

class PersonPageView extends Criteria
{
    public bool $isDelegate = false;
    public bool $isReferee = false;
    public bool $isRefereeObserver = false;
}
