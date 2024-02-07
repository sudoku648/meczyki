<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Domain\Event;

use Sudoku648\Meczyki\User\Domain\Entity\User;

readonly class UserUpdatedEvent
{
    public function __construct(
        public User $user,
    ) {
    }
}
