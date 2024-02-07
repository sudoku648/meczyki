<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameCreatedEvent;
use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameDeletedEvent;
use Sudoku648\Meczyki\MatchGame\Domain\Event\MatchGameUpdatedEvent;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameManagerInterface;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Round;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Season;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Venue;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Sudoku648\Meczyki\MatchGame\Frontend\Factory\MatchGameFactory;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class MatchGameManager implements MatchGameManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MatchGameFactory $factory,
    ) {
    }

    public function create(MatchGameDto $dto, User $user): MatchGame
    {
        $matchGame = $this->factory->createFromDto($dto, $user);

        $matchGame = $this->setNonProfitableValues($matchGame);

        $this->entityManager->persist($matchGame);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new MatchGameCreatedEvent($matchGame));

        return $matchGame;
    }

    public function edit(MatchGame $matchGame, MatchGameDto $dto): MatchGame
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

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new MatchGameUpdatedEvent($matchGame));

        return $matchGame;
    }

    public function delete(MatchGame $matchGame): void
    {
        $this->dispatcher->dispatch(new MatchGameDeletedEvent($matchGame));

        $this->entityManager->remove($matchGame);
        $this->entityManager->flush();
    }

    public function createDto(MatchGame $matchGame): MatchGameDto
    {
        return $this->factory->createDto($matchGame);
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
