<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\MatchGame;
use App\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

class MatchGameBillDto
{
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

    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}