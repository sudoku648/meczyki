<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Override;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Round;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Season;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Venue;

use function mt_rand;

class MatchGameFixtures extends BaseFixture implements DependentFixtureInterface
{
    private const int MATCH_GAMES_COUNT = 22;

    #[Override]
    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomMatchGames(self::MATCH_GAMES_COUNT) as $index => $matchGame) {
            $newMatchGame = new MatchGame(
                $matchGame['user'],
                $matchGame['homeTeam'],
                $matchGame['awayTeam'],
                $matchGame['dateTime'],
                $matchGame['gameType'],
                null !== $matchGame['season'] ? Season::fromString($matchGame['season']) : null,
                null !== $matchGame['round'] ? Round::byValue($matchGame['round']) : null,
                Venue::fromString($matchGame['venue']),
                $matchGame['referee'],
                $matchGame['firstAssistantReferee'],
                false,
                $matchGame['secondAssistantReferee'],
                false,
                null,
                $matchGame['refereeObserver'],
                $matchGame['delegate'],
                null
            );

            $manager->persist($newMatchGame);

            $this->addReference("match_game_$index", $newMatchGame);

            $manager->flush();
        }
    }

    private function provideRandomMatchGames(int $count = 1): iterable
    {
        for ($i = 0; $i < $count; $i++) {
            yield [
                'user'                   => $this->getReference(
                    'user' . '_' . mt_rand(0, UserFixtures::USERS_COUNT - 1)
                ),
                'homeTeam'               => $this->getReference(
                    'team' . '_' . mt_rand(0, TeamFixtures::TEAMS_COUNT - 1)
                ),
                'awayTeam'               => $this->getReference(
                    'team' . '_' . mt_rand(0, TeamFixtures::TEAMS_COUNT - 1)
                ),
                'dateTime'               => $this->getRandomDateTime(),
                'gameType'               => $this->getReference(
                    'game_type' . '_' . mt_rand(0, GameTypeFixtures::GAME_TYPES_COUNT - 1)
                ),
                'season'                 => null,
                'round'                  => null,
                'venue'                  => $this->faker->city(),
                'referee'                => $this->getReference(
                    'referee' . '_' . mt_rand(0, RefereeFixtures::REFEREES_COUNT - 1)
                ),
                'firstAssistantReferee'  => mt_rand(0, 1)
                    ? $this->getReference(
                        'referee' . '_' . mt_rand(0, RefereeFixtures::REFEREES_COUNT - 1)
                    )
                    : null,
                'secondAssistantReferee' => mt_rand(0, 1)
                    ? $this->getReference(
                        'referee' . '_' . mt_rand(0, RefereeFixtures::REFEREES_COUNT - 1)
                    )
                    : null,
                'refereeObserver'        => mt_rand(0, 1)
                    ? $this->getReference(
                        'referee_observer' . '_' . mt_rand(0, RefereeObserverFixtures::REFEREE_OBSERVERS_COUNT - 1)
                    )
                    : null,
                'delegate'               => mt_rand(0, 1)
                    ? $this->getReference(
                        'delegate' . '_' . mt_rand(0, DelegateFixtures::DELEGATES_COUNT - 1)
                    )
                    : null,
            ];
        }
    }

    public function getDependencies(): array
    {
        return [
            DelegateFixtures::class,
            GameTypeFixtures::class,
            RefereeFixtures::class,
            RefereeObserverFixtures::class,
            TeamFixtures::class,
            UserFixtures::class,
        ];
    }
}
