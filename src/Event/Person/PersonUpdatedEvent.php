<?php

declare(strict_types=1);

namespace App\Event\Person;

use App\Entity\Person;

class PersonUpdatedEvent
{
    public function __construct(
        private Person $person
    ) {
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
