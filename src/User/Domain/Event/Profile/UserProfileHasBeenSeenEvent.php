<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Domain\Event\Profile;

use Sudoku648\Meczyki\User\Domain\Entity\User;

readonly class UserProfileHasBeenSeenEvent
{
    public function __construct(
        public User $user,
    ) {
    }
}
