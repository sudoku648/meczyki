<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

class DelegateFixtures extends BaseFixture
{
    public const int DELEGATES_COUNT = 8;

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomDelegates(self::DELEGATES_COUNT) as $index => $delegate) {
            $newDelegate = new Person(
                FirstName::fromString($delegate['firstName']),
                LastName::fromString($delegate['lastName']),
                null !== $delegate['mobilePhone'] ? PhoneNumber::fromString($delegate['mobilePhone']) : null,
                ['delegate'],
            );

            $manager->persist($newDelegate);

            $this->addReference("delegate_$index", $newDelegate);

            $manager->flush();
        }
    }

    private function provideRandomDelegates(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            yield [
                'firstName'   => $this->faker->firstNameMale(),
                'lastName'    => $this->faker->lastNameMale(),
                'mobilePhone' => null,
            ];
        }
    }
}
