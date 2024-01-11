<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\MatchGame;
use App\Entity\Person;
use App\ValueObject\MatchGameBillId;
use Symfony\Component\Validator\Constraints as Assert;

class MatchGameBillDto
{
    private ?MatchGameBillId $id = null;

    public ?Person $person = null;

    public ?MatchGame $matchGame = null;

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

    public function getId(): ?MatchGameBillId
    {
        return $this->id;
    }

    public function setId(MatchGameBillId $id): void
    {
        $this->id = $id;
    }
}
