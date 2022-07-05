<?php

declare(strict_types=1);

namespace App\Message\Flash\MatchGame;

use App\Message\Contracts\FlashMessageInterface;

class MatchGameNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie mecze zostały usunięte.';
    }
}
