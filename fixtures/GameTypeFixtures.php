<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;

use function ucfirst;

class GameTypeFixtures extends BaseFixture
{
    public const int GAME_TYPES_COUNT = 13;

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomGameTypes(self::GAME_TYPES_COUNT) as $index => $gameType) {
            $newGameType = new GameType(
                GameTypeName::fromString($gameType['name']),
                true
            );

            $manager->persist($newGameType);

            $this->addReference("game_type_$index", $newGameType);

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
