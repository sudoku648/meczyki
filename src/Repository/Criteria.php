<?php

declare(strict_types=1);

namespace App\Repository;

abstract class Criteria
{
    public int $page = 1;

    public function __construct(int $page)
    {
        $this->page = $page;
    }
}
