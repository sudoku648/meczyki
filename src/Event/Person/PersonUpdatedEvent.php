<?php

declare(strict_types=1);

namespace App\Event\Person;

use App\Entity\Person;

readonly class PersonUpdatedEvent
{
    public function __construct(
        public Person $person,
    ) {
    }
}
