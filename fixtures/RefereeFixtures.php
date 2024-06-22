<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

use function mt_rand;

class RefereeFixtures extends BaseFixture
{
    public const REFEREES_COUNT = 22;

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomReferees(self::REFEREES_COUNT) as $index => $referee) {
            $newReferee = new Person(
                FirstName::fromString($referee['firstName']),
                LastName::fromString($referee['lastName']),
                null !== $referee['mobilePhone'] ? PhoneNumber::fromString($referee['mobilePhone']) : null,
                ['referee'],
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
