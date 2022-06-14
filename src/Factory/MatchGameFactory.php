<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\MatchGameDto;
use App\Entity\MatchGame;
use App\Entity\User;
use App\Factory\Contracts\ContentFactoryInterface;

class MatchGameFactory implements ContentFactoryInterface
{
    public function createFromDto(MatchGameDto $dto, User $user): MatchGame
    {
        return new MatchGame(
            $user,
            $dto->homeTeam,
            $dto->awayTeam,
            $dto->dateTime,
            $dto->gameType,
            $dto->season,
            $dto->round,
            $dto->venue,
            $dto->referee,
            $dto->firstAssistantReferee,
            $dto->isFirstAssistantNonProfitable,
            $dto->secondAssistantReferee,
            $dto->isSecondAssistantNonProfitable,
            $dto->fourthOfficial,
            $dto->refereeObserver,
            $dto->delegate,
            $dto->moreInfo
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
        $dto->season                         = $matchGame->getSeason();
        $dto->round                          = $matchGame->getRound();
        $dto->venue                          = $matchGame->getVenue();
        $dto->referee                        = $matchGame->getReferee();
        $dto->firstAssistantReferee          = $matchGame->getFirstAssistantReferee();
        $dto->isFirstAssistantNonProfitable  = $matchGame->isFirstAssistantNonProfitable();
        $dto->secondAssistantReferee         = $matchGame->getSecondAssistantReferee();
        $dto->isSecondAssistantNonProfitable = $matchGame->isSecondAssistantNonProfitable();
        $dto->fourthOfficial                 = $matchGame->getFourthOfficial();
        $dto->refereeObserver                = $matchGame->getRefereeObserver();
        $dto->delegate                       = $matchGame->getDelegate();
        $dto->moreInfo                       = $matchGame->getMoreInfo();
        $dto->createdAt                      = $matchGame->getCreatedAt();
        $dto->createdAtAgo                   = $matchGame->getCreatedAtAgo();
        $dto->updatedAt                      = $matchGame->getUpdatedAt();
        $dto->updatedAtAgo                   = $matchGame->getUpdatedAtAgo();
        $dto->setId($matchGame->getId());

        return $dto;
    }
}
