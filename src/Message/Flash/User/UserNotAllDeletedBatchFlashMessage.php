<?php

declare(strict_types=1);

namespace App\Message\Flash\User;

use App\Message\Contracts\FlashMessageInterface;

class UserNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszyscy użytkownicy zostali usunięci.';
    }
}
