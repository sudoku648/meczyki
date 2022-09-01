<?php

declare(strict_types=1);

namespace App\Event\User;

use App\Entity\User;

class UserBindWithPersonEvent
{
    public function __construct(
        private User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
