<?php

declare(strict_types=1);

namespace App\Event\User\Profile;

use App\Entity\User;

class UserProfileHasBeenSeenEvent
{
    public function __construct(
        public readonly User $user,
    ) {
    }
}
