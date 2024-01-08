<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\MatchGameDto;
use App\Entity\MatchGame;
use App\Entity\User;
use App\Event\MatchGame\MatchGameCreatedEvent;
use App\Event\MatchGame\MatchGameDeletedEvent;
use App\Event\MatchGame\MatchGameUpdatedEvent;
use App\Factory\MatchGameFactory;
use App\Service\Contracts\MatchGameManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
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
        $matchGame->setSeason($dto->season);
        $matchGame->setRound($dto->round);
        $matchGame->setVenue($dto->venue);
        $matchGame->setReferee($dto->referee);
        $matchGame->setFirstAssistantReferee($dto->firstAssistantReferee);
        $matchGame->setFirstAssistantNonProfitable($dto->isFirstAssistantNonProfitable);
        $matchGame->setSecondAssistantReferee($dto->secondAssistantReferee);
        $matchGame->setSecondAssistantNonProfitable($dto->isSecondAssistantNonProfitable);
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
