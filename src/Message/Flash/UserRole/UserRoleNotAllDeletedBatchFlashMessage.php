<?php

declare(strict_types=1);

namespace App\Message\Flash\UserRole;

use App\Message\Contracts\FlashMessageInterface;

class UserRoleNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie role użytkowników zostały usunięte.';
    }
}
