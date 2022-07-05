<?php

declare(strict_types=1);

namespace App\Message\Flash\MatchGame;

use App\Message\Contracts\FlashMessageInterface;

class MatchGameDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Mecze zostały usunięte.';
    }
}
