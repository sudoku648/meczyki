<?php

declare(strict_types=1);

namespace App\Message\Flash\Person;

use App\Message\Contracts\PersonFlashMessageInterface;

class PersonPersonalInfoUpdatedFlashMessage implements PersonFlashMessageInterface
{
    private int $personId;

    public function __construct(int $personId)
    {
        $this->personId = $personId;
    }

    public function getPersonId(): int
    {
        return $this->personId;
    }

    public function getMessage(): string
    {
        return 'Dane osobowe zostały zaktualizowane.';
    }
}