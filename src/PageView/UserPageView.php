<?php

declare(strict_types=1);

namespace App\PageView;

use App\Repository\Criteria;

class UserPageView extends Criteria
{
    public ?string $usernameLike = null;
}
