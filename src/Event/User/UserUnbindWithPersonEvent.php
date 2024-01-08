<?php

declare(strict_types=1);

namespace App\Event\User;

use App\Entity\User;

readonly class UserUnbindWithPersonEvent
{
    public function __construct(
        public User $user,
    ) {
    }
}
