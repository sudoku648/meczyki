<?php

declare(strict_types=1);

namespace App\Message\Flash\Team;

use App\Message\Contracts\FlashMessageInterface;

class TeamDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Drużyny zostały usunięte.';
    }
}
