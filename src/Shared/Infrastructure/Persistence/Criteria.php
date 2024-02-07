<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Infrastructure\Persistence;

abstract class Criteria
{
    public function __construct(
        public int $page = 1,
        public ?int $perPage = null
    ) {
    }
}
