<?php

declare(strict_types=1);

namespace App\Event\User;

use App\Entity\User;

class UserUnbindWithPersonEvent
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
