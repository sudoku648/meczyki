<?php

declare(strict_types=1);

namespace App\Message\Flash\GameType;

use App\Message\Contracts\FlashMessageInterface;

class GameTypeNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie typy rozgrywek zostały usunięte.';
    }
}
