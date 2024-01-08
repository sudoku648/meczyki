<?php

declare(strict_types=1);

namespace App\Event\UserRole;

use App\Entity\UserRole;

readonly class UserRoleCreatedEvent
{
    public function __construct(
        public UserRole $userRole,
    ) {
    }
}
