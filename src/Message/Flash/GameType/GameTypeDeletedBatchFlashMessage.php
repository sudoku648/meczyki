<?php

declare(strict_types=1);

namespace App\Message\Flash\GameType;

use App\Message\Contracts\FlashMessageInterface;

class GameTypeDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Typy rozgrywek zostały usunięte.';
    }
}
