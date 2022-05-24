<?php

declare(strict_types=1);

namespace App\Event\Person;

use App\Entity\Person;

class PersonUpdatedEvent
{
    private Person $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
