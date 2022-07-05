<?php

declare(strict_types=1);

namespace App\Message\Flash\Club;

use App\Message\Contracts\FlashMessageInterface;

class ClubDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Kluby zostały usunięte.';
    }
}
