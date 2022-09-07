<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;

class ClubPageView extends Criteria
{
    public ?string $nameLike = null;
}
