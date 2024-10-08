<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Domain\Entity;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\MatchGameId;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Round;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Season;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Venue;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Shared\Domain\Entity\CreatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\Entity\UpdatedAtTrait;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\User\Domain\Entity\User;

class MatchGame
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private MatchGameId $id;
    private ?User $user;
    private ?Team $homeTeam;
    private ?Team $awayTeam;
    private DateTimeImmutable $dateTime;
    private ?GameType $gameType;
    private ?Season $season;
    private ?Round $round;
    private Venue $venue;
    private ?Person $referee;
    private ?Person $firstAssistantReferee;
    private ?bool $isFirstAssistantNonProfitable;
    private ?Person $secondAssistantReferee;
    private ?bool $isSecondAssistantNonProfitable;
    private ?Person $fourthOfficial;
    private ?Person $refereeObserver;
    private ?Person $delegate;
    private string $moreInfo;
    private Collection $matchGameBills;

    public function __construct(
        User $user,
        Team $homeTeam,
        Team $awayTeam,
        DateTimeImmutable $dateTime,
        GameType $gameType,
        ?Season $season,
        ?Round $round,
        Venue $venue,
        Person $referee,
        ?Person $firstAssistantReferee,
        ?bool $isFirstAssistantNonProfitable,
        ?Person $secondAssistantReferee,
        ?bool $isSecondAssistantNonProfitable,
        ?Person $fourthOfficial,
        ?Person $refereeObserver,
        ?Person $delegate,
        ?string $moreInfo,
    ) {
        $this->id                             = MatchGameId::generate();
        $this->user                           = $user;
        $this->homeTeam                       = $homeTeam;
        $this->awayTeam                       = $awayTeam;
        $this->dateTime                       = $dateTime;
        $this->gameType                       = $gameType;
        $this->season                         = $season;
        $this->round                          = $round;
        $this->venue                          = $venue;
        $this->referee                        = $referee;
        $this->firstAssistantReferee          = $firstAssistantReferee;
        $this->isFirstAssistantNonProfitable  = $isFirstAssistantNonProfitable;
        $this->secondAssistantReferee         = $secondAssistantReferee;
        $this->isSecondAssistantNonProfitable = $isSecondAssistantNonProfitable;
        $this->fourthOfficial                 = $fourthOfficial;
        $this->refereeObserver                = $refereeObserver;
        $this->delegate                       = $delegate;
        $this->moreInfo                       = $moreInfo ?? '';
        $this->matchGameBills                 = new ArrayCollection();

        $this->createdAtTraitConstruct();
    }

    public function getId(): MatchGameId
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $team): self
    {
        $this->homeTeam = $team;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $team): self
    {
        $this->awayTeam = $team;

        return $this;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTimeImmutable $dateTime): self
    {
        $this->dateTime = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $dateTime->format('Y-m-d H:i'),
            new DateTimeZone('Europe/Warsaw')
        );

        return $this;
    }

    public function getGameType(): ?GameType
    {
        return $this->gameType;
    }

    public function setGameType(?GameType $gameType): self
    {
        $this->gameType = $gameType;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    public function setVenue(Venue $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getReferee(): ?Person
    {
        return $this->referee;
    }

    public function setReferee(?Person $referee): self
    {
        $this->referee = $referee;

        return $this;
    }

    public function getFirstAssistantReferee(): ?Person
    {
        return $this->firstAssistantReferee;
    }

    public function setFirstAssistantReferee(?Person $referee): self
    {
        $this->firstAssistantReferee = $referee;

        return $this;
    }

    public function isFirstAssistantNonProfitable(): ?bool
    {
        return $this->isFirstAssistantNonProfitable;
    }

    public function setFirstAssistantNonProfitable(?bool $nonProfitable): self
    {
        $this->isFirstAssistantNonProfitable = $nonProfitable;

        return $this;
    }

    public function getSecondAssistantReferee(): ?Person
    {
        return $this->secondAssistantReferee;
    }

    public function setSecondAssistantReferee(?Person $referee): self
    {
        $this->secondAssistantReferee = $referee;

        return $this;
    }

    public function isSecondAssistantNonProfitable(): ?bool
    {
        return $this->isSecondAssistantNonProfitable;
    }

    public function setSecondAssistantNonProfitable(?bool $nonProfitable): self
    {
        $this->isSecondAssistantNonProfitable = $nonProfitable;

        return $this;
    }

    public function getFourthOfficial(): ?Person
    {
        return $this->fourthOfficial;
    }

    public function setFourthOfficial(?Person $referee): self
    {
        $this->fourthOfficial = $referee;

        return $this;
    }

    public function getRefereeObserver(): ?Person
    {
        return $this->refereeObserver;
    }

    public function setRefereeObserver(?Person $refereeObserver): self
    {
        $this->refereeObserver = $refereeObserver;

        return $this;
    }

    public function getDelegate(): ?Person
    {
        return $this->delegate;
    }

    public function setDelegate(?Person $delegate): self
    {
        $this->delegate = $delegate;

        return $this;
    }

    public function getMoreInfo(): string
    {
        return $this->moreInfo;
    }

    public function setMoreInfo(string $moreInfo): self
    {
        $this->moreInfo = $moreInfo;

        return $this;
    }

    /**
     * @return Collection<int, MatchGameBill>
     */
    public function getMatchGameBills(): Collection
    {
        return $this->matchGameBills;
    }

    public function addMatchGameBill(MatchGameBill $matchGameBill): self
    {
        if (!$this->matchGameBills->contains($matchGameBill)) {
            $this->matchGameBills[] = $matchGameBill;
            $matchGameBill->setMatchGame($this);
        }

        return $this;
    }

    public function removeMatchGameBill(MatchGameBill $matchGameBill): self
    {
        if ($this->matchGameBills->removeElement($matchGameBill)) {
            // set the owning side to null (unless already changed)
            if ($matchGameBill->getMatchGame() === $this) {
                $matchGameBill->setMatchGame(null);
            }
        }

        return $this;
    }

    public function getBillOfPerson(?Person $person): ?MatchGameBill
    {
        if (!$person) {
            return null;
        }

        $bill = $this->matchGameBills->filter(function ($element) use ($person) {
            return $element->getPerson() === $person;
        })->first();

        return $bill ?: null;
    }

    public function getCompetitors(): string
    {
        return
            ($this->homeTeam?->getName() ?? '') .
            ' - ' .
            ($this->awayTeam?->getName() ?? '')
        ;
    }
}
