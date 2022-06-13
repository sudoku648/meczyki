<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testNames(): void
    {
        $person = new Person('Jan', 'Kowalski');

        $this->assertEquals('Jan', $person->getFirstName());
        $this->assertEquals('Kowalski', $person->getLastName());
        $this->assertEquals('Kowalski Jan', $person->getFullName());
        $this->assertEquals('Jan Kowalski', $person->getFullNameInversed());

        $person->setFirstName('Adam');

        $this->assertEquals('Adam', $person->getFirstName());
        $this->assertEquals('Kowalski', $person->getLastName());
        $this->assertEquals('Kowalski Adam', $person->getFullName());
        $this->assertEquals('Adam Kowalski', $person->getFullNameInversed());

        $person->setLastName('Nowak');

        $this->assertEquals('Adam', $person->getFirstName());
        $this->assertEquals('Nowak', $person->getLastName());
        $this->assertEquals('Nowak Adam', $person->getFullName());
        $this->assertEquals('Adam Nowak', $person->getFullNameInversed());
    }
}
