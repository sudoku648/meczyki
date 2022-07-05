<?php

declare(strict_types=1);

namespace App\Message\Flash\Person;

use App\Message\Contracts\FlashMessageInterface;

class PersonDeletedBatchFlashMessage implements FlashMessageInterface
{
    public function getMessage(): string
    {
        return 'Osoby zostały usunięte.';
    }
}
