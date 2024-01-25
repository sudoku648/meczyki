<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\GameType;
use App\ValueObject\GameTypeName;
use Doctrine\Persistence\ObjectManager;

use function ucfirst;

class GameTypeFixtures extends BaseFixture
{
    public const GAME_TYPES_COUNT = 13;

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomGameTypes(self::GAME_TYPES_COUNT) as $index => $gameType) {
            $newGameType = new GameType(
                GameTypeName::fromString($gameType['name']),
                true
            );

            $manager->persist($newGameType);

            $this->addReference('game_type' . '_' . $index, $newGameType);

            $manager->flush();
        }
    }

    private function provideRandomGameTypes(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            yield [
                'name'  => ucfirst($this->faker->word()),
            ];
        }
    }
}
