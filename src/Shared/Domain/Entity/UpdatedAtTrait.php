<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\Entity;

use Carbon\Carbon;
use DateTimeImmutable;

trait UpdatedAtTrait
{
    private ?DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): DateTimeImmutable
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this->updatedAt;
    }

    public function getUpdatedAtAgo(): ?string
    {
        return $this->updatedAt ? Carbon::instance($this->updatedAt)->diffForHumans() : null;
    }
}
