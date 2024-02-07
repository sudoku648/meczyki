<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\Entity;

use Carbon\Carbon;
use DateTimeImmutable;

trait CreatedAtTrait
{
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->createdAt)->diffForHumans();
    }
}
