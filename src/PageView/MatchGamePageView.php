<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;

class MatchGamePageView extends Criteria
{
    public ?string $dateTimeLike = null;
    public ?string $gameTypeLike = null;
    public ?string $teamsLike    = null;
}
