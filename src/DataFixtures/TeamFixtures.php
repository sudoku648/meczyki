<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends BaseFixture implements DependentFixtureInterface
{
    const TEAMS_COUNT = 25;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomTeams(self::TEAMS_COUNT) as $index => $team) {
            $newTeam = new Team(
                $team['fullName'],
                $team['shortName'],
                $team['club']
            );

            $manager->persist($newTeam);

            $this->addReference('team'.'_'.$index, $newTeam);

            $manager->flush();
        }

    }

    private function provideRandomTeams($count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            $shortName = \ucfirst($this->faker->word).' '.$this->faker->city;
            $fullName = \array_rand(\array_flip(['G', 'L', 'K', 'Z', 'M']), 1).'KS '.$shortName;

            yield [
                'fullName'  => $fullName,
                'shortName' => $shortName,
                'club'      => $this->getReference(
                    'club'.'_'.\mt_rand(0, ClubFixtures::CLUBS_COUNT - 1)
                ),
            ];
        }
    }

    public function getDependencies(): array
    {
        return [
            ClubFixtures::class,
        ];
    }
}
