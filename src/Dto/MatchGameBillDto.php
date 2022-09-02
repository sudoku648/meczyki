<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\MatchGame;
use App\Entity\Person;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class MatchGameBillDto
{
    private ?string $id = null;

    public Person|PersonDto|null $person = null;

    public MatchGame|MatchGameDto|null $matchGame = null;

    #[Assert\NotBlank()]
    #[Assert\GreaterThanOrEqual(value: 0)]
    public ?int $baseEquivalent = null;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 0, max: 100)]
    public ?int $percentOfBaseEquivalent = null;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 0, max: 100)]
    public ?int $taxDeductibleStakePercent = null;

    #[Assert\NotBlank()]
    #[Assert\Range(min: 0, max: 100)]
    public ?int $incomeTaxStakePercent = null;

    public ?DateTimeImmutable $createdAt = null;

    public ?string $createdAtAgo = null;

    public ?DateTimeImmutable $updatedAt = null;

    public ?string $updatedAtAgo = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
