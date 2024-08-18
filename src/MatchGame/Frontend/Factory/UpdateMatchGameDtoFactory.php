<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Factory;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\UpdateMatchGameDto;

class UpdateMatchGameDtoFactory
{
    public static function fromEntity(MatchGame $matchGame): UpdateMatchGameDto
    {
        return new UpdateMatchGameDto(
            $matchGame->getId(),
            $matchGame->getUser(),
            $matchGame->getHomeTeam(),
            $matchGame->getAwayTeam(),
            $matchGame->getDateTime(),
            $matchGame->getGameType(),
            $matchGame->getSeason()?->getValue(),
            $matchGame->getRound()?->getValue(),
            $matchGame->getVenue()->getValue(),
            $matchGame->getReferee(),
            $matchGame->getFirstAssistantReferee(),
            $matchGame->isFirstAssistantNonProfitable(),
            $matchGame->getSecondAssistantReferee(),
            $matchGame->isSecondAssistantNonProfitable(),
            $matchGame->getFourthOfficial(),
            $matchGame->getRefereeObserver(),
            $matchGame->getDelegate(),
            $matchGame->getMoreInfo(),
        );
    }
}
