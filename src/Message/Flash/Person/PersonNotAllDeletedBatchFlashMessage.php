<?php

declare(strict_types=1);

namespace App\Message\Flash\Person;

use App\Message\Contracts\FlashMessageInterface;

class PersonNotAllDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Nie wszystkie osoby zostały usunięte.';
    }
}
