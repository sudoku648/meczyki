<?php

declare(strict_types=1);

namespace App\Event\UserRole;

use App\Entity\UserRole;

readonly class UserRoleUpdatedEvent
{
    public function __construct(
        public UserRole $userRole,
    ) {
    }
}
