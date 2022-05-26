<?php

declare(strict_types=1);

namespace App\Event\User\Profile;

use App\Entity\User;

class UserProfileHasBeenSeenEvent
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
