<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

class RefereeObserverFixtures extends BaseFixture
{
    public const REFEREE_OBSERVERS_COUNT = 8;

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach (
            $this->provideRandomRefereeObservers(self::REFEREE_OBSERVERS_COUNT) as $index => $refereeObserver
        ) {
            $newRefereeObserver = new Person(
                FirstName::fromString($refereeObserver['firstName']),
                LastName::fromString($refereeObserver['lastName']),
                null !== $refereeObserver['mobilePhone'] ? PhoneNumber::fromString($refereeObserver['mobilePhone']) : null,
                ['referee_observer'],
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
