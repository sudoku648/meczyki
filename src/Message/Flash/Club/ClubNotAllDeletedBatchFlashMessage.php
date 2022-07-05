<?php

declare(strict_types=1);

namespace App\Message\Flash\Club;

use App\Message\Contracts\FlashMessageInterface;

class ClubNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie kluby zostały usunięte.';
    }
}
