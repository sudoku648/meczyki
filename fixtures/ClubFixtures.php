<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Image\Domain\Persistence\ImageRepositoryInterface;

use function array_flip;
use function array_rand;
use function dirname;
use function mt_rand;
use function ucfirst;

class ClubFixtures extends BaseFixture
{
    public const int CLUBS_COUNT = 13;

    public function __construct(private readonly ImageRepositoryInterface $imageRepository)
    {
    }

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomClubs(self::CLUBS_COUNT) as $index => $club) {
            $newClub = new Club(
                ClubName::fromString($club['name'])
            );

            $randomEmblem = mt_rand(0, 3);
            if ($randomEmblem > 0) {
                $newClub->setEmblem(
                    $this->imageRepository->createFromPath(
                        dirname(__FILE__) . '/images/club-emblem-' . $randomEmblem . '.png'
                    )
                );
            }

            $manager->persist($newClub);

            $this->addReference("club_$index", $newClub);

            $manager->flush();
        }
    }

    private function provideRandomClubs(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            yield [
                'name' => array_rand(
                    array_flip(['G', 'L', 'K', 'Z', 'M']),
                    1
                ) .
                'KS ' .
                ucfirst($this->faker->word()) .
                ' ' .
                $this->faker->city(),
            ];
        }
    }
}
