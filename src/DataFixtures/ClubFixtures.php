<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Club;
use App\Repository\ImageRepository;
use Doctrine\Persistence\ObjectManager;

class ClubFixtures extends BaseFixture
{
    const CLUBS_COUNT = 13;

    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomClubs(self::CLUBS_COUNT) as $index => $club) {
            $newClub = new Club(
                $club['name']
            );

            $randomEmblem = \mt_rand(0, 3);
            if ($randomEmblem > 0) {
                $newClub->setEmblem(
                    $this->imageRepository->createFromPath(
                        \dirname(__FILE__).'/images/club-emblem-'.$randomEmblem.'.png'
                    )
                );
            }

            $manager->persist($newClub);

            $this->addReference('club'.'_'.$index, $newClub);

            $manager->flush();
        }

    }

    private function provideRandomClubs(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            yield [
                'name' => \array_rand(
                    \array_flip(['G', 'L', 'K', 'Z', 'M']), 1
                ).
                'KS '.
                \ucfirst($this->faker->word).
                ' '.
                $this->faker->city,
            ];
        }
    }
}
