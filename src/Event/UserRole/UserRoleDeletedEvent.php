<?php

declare(strict_types=1);

namespace App\Event\UserRole;

use App\Entity\UserRole;

class UserRoleDeletedEvent
{
    public function __construct(
        private UserRole $userRole
    ) {
    }

    public function getUserRole(): UserRole
    {
        return $this->userRole;
    }
}
