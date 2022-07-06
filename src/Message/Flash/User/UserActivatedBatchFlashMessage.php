<?php

declare(strict_types=1);

namespace App\Message\Flash\User;

use App\Message\Contracts\FlashMessageInterface;

class UserActivatedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Użytkownicy zostali aktywowani.';
    }
}
