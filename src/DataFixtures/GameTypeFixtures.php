<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\GameType;
use Doctrine\Persistence\ObjectManager;

class GameTypeFixtures extends BaseFixture
{
    const GAME_TYPES_COUNT = 13;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomGameTypes(self::GAME_TYPES_COUNT) as $index => $gameType) {
            $newGameType = new GameType(
                $gameType['name'],
                $gameType['group'],
                true
            );

            $manager->persist($newGameType);

            $this->addReference('game_type'.'_'.$index, $newGameType);

            $manager->flush();
        }

    }

    private function provideRandomGameTypes($count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            $group = null;
            $hasGroup = \mt_rand(0, 1);
            if ($hasGroup) {
                $group = (string) \mt_rand(1, 8);
            }
            yield [
                'name'  => \ucfirst($this->faker->word),
                'group' => $group,
            ];
        }
    }
}
