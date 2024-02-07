<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;

class PersonTest extends TestCase
{
    public function testNames(): void
    {
        $person = new Person(FirstName::fromString('Jan'), LastName::fromString('Kowalski'));

        $this->assertEquals('Jan', $person->getFirstName()->getValue());
        $this->assertEquals('Kowalski', $person->getLastName()->getValue());
        $this->assertEquals('Kowalski Jan', $person->getFullName());

        $person->setFirstName(FirstName::fromString('Adam'));

        $this->assertEquals('Adam', $person->getFirstName()->getValue());
        $this->assertEquals('Kowalski', $person->getLastName()->getValue());
        $this->assertEquals('Kowalski Adam', $person->getFullName());

        $person->setLastName(LastName::fromString('Nowak'));

        $this->assertEquals('Adam', $person->getFirstName()->getValue());
        $this->assertEquals('Nowak', $person->getLastName()->getValue());
        $this->assertEquals('Nowak Adam', $person->getFullName());
    }
}
