<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Persistence\ObjectManager;

class RefereeFixtures extends BaseFixture
{
    const REFEREES_COUNT = 22;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomReferees(self::REFEREES_COUNT) as $index => $referee) {
            $newReferee = new Person(
                $referee['firstName'],
                $referee['lastName'],
                $referee['mobilePhone'],
                false,
                true,
                false
            );

            $manager->persist($newReferee);

            $this->addReference('referee'.'_'.$index, $newReferee);

            $manager->flush();
        }

    }

    private function provideRandomReferees(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            $sex = \mt_rand(0, 9);

            yield [
                'firstName'   => $sex > 0 ? $this->faker->firstNameMale : $this->faker->firstNameFemale,
                'lastName'    => $sex > 0 ? $this->faker->lastNameMale : $this->faker->lastNameFemale,
                'mobilePhone' => null,
            ];
        }
    }
}
