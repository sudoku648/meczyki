<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Person;
use App\ValueObject\FirstName;
use App\ValueObject\LastName;
use App\ValueObject\PhoneNumber;
use Doctrine\Persistence\ObjectManager;

use function mt_rand;

class RefereeFixtures extends BaseFixture
{
    public const REFEREES_COUNT = 22;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomReferees(self::REFEREES_COUNT) as $index => $referee) {
            $newReferee = new Person(
                FirstName::fromString($referee['firstName']),
                LastName::fromString($referee['lastName']),
                null !== $referee['mobilePhone'] ? PhoneNumber::fromString($referee['mobilePhone']) : null,
                false,
                true,
                false
            );

            $manager->persist($newReferee);

            $this->addReference('referee' . '_' . $index, $newReferee);

            $manager->flush();
        }
    }

    private function provideRandomReferees(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            $sex = mt_rand(0, 9);

            yield [
                'firstName'   => $sex > 0 ? $this->faker->firstNameMale() : $this->faker->firstNameFemale(),
                'lastName'    => $sex > 0 ? $this->faker->lastNameMale() : $this->faker->lastNameFemale(),
                'mobilePhone' => null,
            ];
        }
    }
}
