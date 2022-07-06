<?php

declare(strict_types=1);

namespace App\Message\Flash\User;

use App\Message\Contracts\FlashMessageInterface;

class UserNotAllDeactivatedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszyscy użytkownicy zostali dezaktywowani.';
    }
}
