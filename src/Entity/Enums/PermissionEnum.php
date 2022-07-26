<?php

declare(strict_types=1);

namespace App\Entity\Enums;

enum PermissionEnum: string
{
    case MANAGE_USERS = 'manage_users';

    public function getLabel(): string
    {
        return match ($this) {
            self::MANAGE_USERS => 'Zarządzanie użytkownikami',
            default            => throw new \LogicException(
                \sprintf(
                    'Permission "%s" has no label.',
                    $this->name
                )
            ),
        };
    }
}
