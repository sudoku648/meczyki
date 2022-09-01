<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
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
                $refereeObserver['firstName'],
                $refereeObserver['lastName'],
                $refereeObserver['mobilePhone'],
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
