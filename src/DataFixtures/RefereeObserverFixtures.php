<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use App\ValueObject\FirstName;
use App\ValueObject\LastName;
use App\ValueObject\PhoneNumber;
use Doctrine\Persistence\ObjectManager;

class RefereeObserverFixtures extends BaseFixture
{
    public const REFEREE_OBSERVERS_COUNT = 8;

    public function loadData(ObjectManager $manager): void
    {
        foreach (
            $this->provideRandomRefereeObservers(self::REFEREE_OBSERVERS_COUNT) as $index => $refereeObserver
        ) {
            $newRefereeObserver = new Person(
                FirstName::fromString($refereeObserver['firstName']),
                LastName::fromString($refereeObserver['lastName']),
                null !== $refereeObserver['mobilePhone'] ? PhoneNumber::fromString($refereeObserver['mobilePhone']) : null,
                false,
                false,
                true
            );

            $manager->persist($newRefereeObserver);

            $this->addReference('referee_observer' . '_' . $index, $newRefereeObserver);

            $manager->flush();
        }
    }

    private function provideRandomRefereeObservers(int $count = 1): iterable
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
