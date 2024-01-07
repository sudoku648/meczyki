<?php

declare(strict_types=1);

namespace App\Event\Person;

use App\Entity\Person;

class PersonPersonalInfoUpdatedEvent
{
    public function __construct(
        public readonly Person $person,
    ) {
    }
}
