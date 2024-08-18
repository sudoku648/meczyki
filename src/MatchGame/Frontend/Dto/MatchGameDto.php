<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Dto;

use DateTimeImmutable;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\MatchGameId;
use Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints as MatchGameAssert;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\User\Domain\Entity\User;

#[MatchGameAssert\MatchGameSeasonAndDate]
#[MatchGameAssert\MatchGameSeasonAndRound]
#[MatchGameAssert\MatchGameDuplicatePeople]
final class MatchGameDto
{
    public function __construct(
        public ?MatchGameId $matchGameId = null,
        public ?User $user = null,
        #[MatchGameAssert\HomeTeamRequirements]
        public ?Team $homeTeam = null,
        #[MatchGameAssert\AwayTeamRequirements]
        public ?Team $awayTeam = null,
        #[MatchGameAssert\DateTimeRequirements]
        public ?DateTimeImmutable $dateTime = null,
        #[MatchGameAssert\GameTypeRequirements]
        public ?GameType $gameType = null,
        public ?string $season = null,
        #[MatchGameAssert\RoundRequirements]
        public ?int $round = null,
        #[MatchGameAssert\VenueRequirements]
        public ?string $venue = null,
        #[MatchGameAssert\RefereeRequirements]
        public ?Person $referee = null,
        public ?Person $firstAssistantReferee = null,
        public ?bool $isFirstAssistantNonProfitable = null,
        public ?Person $secondAssistantReferee = null,
        public ?bool $isSecondAssistantNonProfitable = null,
        public ?Person $fourthOfficial = null,
        public ?Person $refereeObserver = null,
        public ?Person $delegate = null,
        public ?string $moreInfo = null,
    ) {
    }
}
