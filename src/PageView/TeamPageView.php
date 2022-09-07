<?php

declare(strict_types=1);

namespace App\PageView;

use App\Entity\Club;
use App\Repository\Criteria;

class TeamPageView extends Criteria
{
    public ?Club $club           = null;
    public ?string $nameLike     = null;
    public ?string $clubNameLike = null;
}
