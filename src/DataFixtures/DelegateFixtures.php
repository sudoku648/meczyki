<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use App\ValueObject\FirstName;
use App\ValueObject\LastName;
use App\ValueObject\PhoneNumber;
use Doctrine\Persistence\ObjectManager;

class DelegateFixtures extends BaseFixture
{
    public const DELEGATES_COUNT = 8;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomDelegates(self::DELEGATES_COUNT) as $index => $delegate) {
            $newDelegate = new Person(
                FirstName::fromString($delegate['firstName']),
                LastName::fromString($delegate['lastName']),
                null !== $delegate['mobilePhone'] ? PhoneNumber::fromString($delegate['mobilePhone']) : null,
                true,
                false,
                false
            );

            $manager->persist($newDelegate);

            $this->addReference('delegate' . '_' . $index, $newDelegate);

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
