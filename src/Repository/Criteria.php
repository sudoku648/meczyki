<?php

declare(strict_types=1);

namespace App\Repository;

abstract class Criteria
{
    public function __construct(
        public int $page = 1,
        public ?int $perPage = null
    ) {
    }
}
