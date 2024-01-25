<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\MatchGameDto;
use App\Entity\MatchGame;
use App\Entity\User;
use App\ValueObject\Round;
use App\ValueObject\Season;
use App\ValueObject\Venue;

class MatchGameFactory
{
    public function createFromDto(MatchGameDto $dto, User $user): MatchGame
    {
        return new MatchGame(
            $user,
            $dto->homeTeam,
            $dto->awayTeam,
            $dto->dateTime,
            $dto->gameType,
            null !== $dto->season ? Season::fromString($dto->season) : null,
            null !== $dto->round ? Round::byValue($dto->round) : null,
            Venue::fromString($dto->venue),
            $dto->referee,
            $dto->firstAssistantReferee,
            true === $dto->isFirstAssistantNonProfitable,
            $dto->secondAssistantReferee,
            true === $dto->isSecondAssistantNonProfitable,
            $dto->fourthOfficial,
            $dto->refereeObserver,
            $dto->delegate,
            $dto->moreInfo,
        );
    }

    public function createDto(MatchGame $matchGame): MatchGameDto
    {
        $dto = new MatchGameDto();

        $dto->user                           = $matchGame->getUser();
        $dto->homeTeam                       = $matchGame->getHomeTeam();
        $dto->awayTeam                       = $matchGame->getAwayTeam();
        $dto->dateTime                       = $matchGame->getDateTime();
        $dto->gameType                       = $matchGame->getGameType();
        $dto->season                         = $matchGame->getSeason()?->getValue();
        $dto->round                          = $matchGame->getRound()?->getValue();
        $dto->venue                          = $matchGame->getVenue()->getValue();
        $dto->referee                        = $matchGame->getReferee();
        $dto->firstAssistantReferee          = $matchGame->getFirstAssistantReferee();
        $dto->isFirstAssistantNonProfitable  = $matchGame->isFirstAssistantNonProfitable();
        $dto->secondAssistantReferee         = $matchGame->getSecondAssistantReferee();
        $dto->isSecondAssistantNonProfitable = $matchGame->isSecondAssistantNonProfitable();
        $dto->fourthOfficial                 = $matchGame->getFourthOfficial();
        $dto->refereeObserver                = $matchGame->getRefereeObserver();
        $dto->delegate                       = $matchGame->getDelegate();
        $dto->moreInfo                       = $matchGame->getMoreInfo();
        $dto->setId($matchGame->getId());

        return $dto;
    }
}
