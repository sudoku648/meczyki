<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameManagerInterface;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Round;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Season;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Venue;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\CreateMatchGameDto;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\UpdateMatchGameDto;
use Sudoku648\Meczyki\User\Domain\Entity\User;

readonly class MatchGameManager implements MatchGameManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MatchGameRepositoryInterface $repository,
    ) {
    }

    public function create(CreateMatchGameDto $dto, User $user): MatchGame
    {
        $matchGame = new MatchGame(
            $user,
            $dto->homeTeam,
            $dto->awayTeam,
            $dto->dateTime,
            $dto->gameType,
            $dto->season,
            $dto->round,
            Venue::fromString($dto->venue),
            $dto->referee,
            $dto->firstAssistantReferee,
            $dto->isFirstAssistantNonProfitable,
            $dto->secondAssistantReferee,
            $dto->isSecondAssistantNonProfitable,
            $dto->fourthOfficial,
            $dto->refereeObserver,
            $dto->delegate,
            $dto->moreInfo,
        );

        $matchGame = $this->setNonProfitableValues($matchGame);

        $this->repository->persist($matchGame);

        return $matchGame;
    }

    public function edit(MatchGame $matchGame, UpdateMatchGameDto $dto): MatchGame
    {
        $matchGame->setHomeTeam($dto->homeTeam);
        $matchGame->setAwayTeam($dto->awayTeam);
        $matchGame->setDateTime($dto->dateTime);
        $matchGame->setGameType($dto->gameType);
        $matchGame->setSeason(null !== $dto->season ? Season::fromString($dto->season) : null);
        $matchGame->setRound(null !== $dto->round ? Round::byValue($dto->round) : null);
        $matchGame->setVenue(Venue::fromString($dto->venue));
        $matchGame->setReferee($dto->referee);
        $matchGame->setFirstAssistantReferee($dto->firstAssistantReferee);
        $matchGame->setFirstAssistantNonProfitable(true === $dto->isFirstAssistantNonProfitable);
        $matchGame->setSecondAssistantReferee($dto->secondAssistantReferee);
        $matchGame->setSecondAssistantNonProfitable(true === $dto->isSecondAssistantNonProfitable);
        $matchGame->setFourthOfficial($dto->fourthOfficial);
        $matchGame->setRefereeObserver($dto->refereeObserver);
        $matchGame->setDelegate($dto->delegate);
        $matchGame->setMoreInfo($dto->moreInfo ?? '');

        $matchGame = $this->setNonProfitableValues($matchGame);

        $matchGame->setUpdatedAt();

        $this->repository->persist($matchGame);

        return $matchGame;
    }

    public function delete(MatchGame $matchGame): void
    {
        $this->entityManager->remove($matchGame);
    }

    private function setNonProfitableValues(MatchGame $matchGame): MatchGame
    {
        if (!$matchGame->getFirstAssistantReferee()) {
            $matchGame->setFirstAssistantNonProfitable(null);
        }
        if (!$matchGame->getSecondAssistantReferee()) {
            $matchGame->setSecondAssistantNonProfitable(null);
        }

        return $matchGame;
    }
}
