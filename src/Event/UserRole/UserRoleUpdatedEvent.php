<?php

declare(strict_types=1);

namespace App\Event\UserRole;

use App\Entity\UserRole;

class UserRoleUpdatedEvent
{
    private UserRole $userRole;

    public function __construct(UserRole $userRole)
    {
        $this->userRole = $userRole;
    }

    public function getUserRole(): UserRole
    {
        return $this->userRole;
    }
}
