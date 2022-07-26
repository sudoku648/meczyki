<?php

declare(strict_types=1);

namespace App\Message\Flash\UserRole;

use App\Message\Contracts\UserRoleFlashMessageInterface;

class UserRoleDeletedFlashMessage implements UserRoleFlashMessageInterface
{
    private int $userRoleId;

    public function __construct(int $userRoleId)
    {
        $this->userRoleId = $userRoleId;
    }

    public function getUserRoleId(): int
    {
        return $this->userRoleId;
    }

    public function getMessage(): string
    {
        return 'Rola użytkownika została usunięta.';
    }
}
