<?php

declare(strict_types=1);

namespace App\Message\Flash\UserRole;

use App\Message\Contracts\FlashMessageInterface;

class UserRoleDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Role użytkowników zostały usunięte.';
    }
}
