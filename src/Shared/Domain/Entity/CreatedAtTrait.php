<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\Entity;

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
}
