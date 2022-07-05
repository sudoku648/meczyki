<?php

declare(strict_types=1);

namespace App\Message\Flash\Team;

use App\Message\Contracts\FlashMessageInterface;

class TeamNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie drużyny zostały usunięte.';
    }
}
