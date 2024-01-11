<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\GameType;
use App\Entity\Person;
use App\Entity\Team;
use App\Entity\User;
use App\ValueObject\MatchGameId;
use DateTimeImmutable;
use LogicException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use function array_diff_assoc;
use function array_intersect;
use function array_keys;
use function array_unique;
use function count;
use function explode;
use function get_object_vars;

class MatchGameDto
{
    private ?MatchGameId $id = null;

    public ?User $user = null;

    #[Assert\NotBlank()]
    public ?Team $homeTeam = null;

    #[Assert\NotBlank()]
    public ?Team $awayTeam = null;

    #[Assert\NotBlank()]
    public ?DateTimeImmutable $dateTime = null;

    #[Assert\NotBlank()]
    public ?GameType $gameType = null;

    public ?string $season = null;

    #[Assert\GreaterThanOrEqual(value: 1)]
    public ?int $round = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $venue = null;

    #[Assert\NotBlank()]
    public ?Person $referee = null;

    public ?Person $firstAssistantReferee = null;

    public ?bool $isFirstAssistantNonProfitable = null;

    public ?Person $secondAssistantReferee = null;

    public ?bool $isSecondAssistantNonProfitable = null;

    public ?Person $fourthOfficial = null;

    public ?Person $refereeObserver = null;

    public ?Person $delegate = null;

    public ?string $moreInfo = null;

    #[Assert\Callback]
    public function validateSeasonWithDate(
        ExecutionContextInterface $context,
        $payload
    ) {
        if (!$this->season) {
            return;
        }

        $boundaries = $this->dateBoundaries($this->season);

        if (
            $this->dateTime &&
            (
                $this->dateTime->format('Y-m-d') < $boundaries[0] ||
                $this->dateTime->format('Y-m-d') > $boundaries[1]
            )
        ) {
            $context->buildViolation('Wybrany sezon nie odpowiada dacie meczu.')
                ->atPath('season')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateSeasonAndRoundWithGameType(
        ExecutionContextInterface $context,
        $payload
    ) {
        if ($this->gameType && !$this->gameType->isOfficial() && $this->season) {
            $context->buildViolation('Dla nieoficjalnych rozgrywek nie można podać sezonu.')
                ->atPath('season')
                ->addViolation();
        }

        if ($this->gameType && !$this->gameType->isOfficial() && $this->round) {
            $context->buildViolation('Dla nieoficjalnych rozgrywek nie można podać rundy.')
                ->atPath('round')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateChosenPeople(
        ExecutionContextInterface $context,
        $payload
    ) {
        $allPeopleIds = [];
        foreach (get_object_vars($this) as $prop => $value) {
            if (!$value instanceof Person) {
                continue;
            }

            $allPeopleIds[$prop] = $value->getId();
        }

        $duplicates = array_keys(
            array_intersect(
                $allPeopleIds,
                array_diff_assoc(
                    $allPeopleIds,
                    array_unique($allPeopleIds)
                )
            )
        );

        foreach ($duplicates as $path) {
            $context->buildViolation('Tę samą osobę wybrano więcej niż raz.')
                ->atPath($path)
                ->addViolation();
        }
    }

    /**
     * @param string $season
     * @return array<string>
     */
    private function dateBoundaries(string $season): array
    {
        $years = $this->parseSeasonString($season);

        return [
            $years[0] . '-07-15',
            $years[1] . '-07-05',
        ];
    }

    /**
     * @param string $season
     * @return array<string>
     */
    private function parseSeasonString(string $season): array
    {
        $years = explode('/', $season);
        if (count($years) !== 2) {
            throw new LogicException();
        }

        return $years;
    }

    public function getId(): ?MatchGameId
    {
        return $this->id;
    }

    public function setId(MatchGameId $id): void
    {
        $this->id = $id;
    }

    public function __sleep()
    {
        return [];
    }
}
