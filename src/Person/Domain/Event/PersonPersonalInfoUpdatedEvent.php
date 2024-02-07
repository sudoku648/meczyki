<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Event;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;

readonly class PersonPersonalInfoUpdatedEvent
{
    public function __construct(
        public Person $person,
    ) {
    }
}
