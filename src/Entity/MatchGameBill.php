<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\MatchGameBillId;

class MatchGameBill
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private MatchGameBillId $id;

    private ?Person $person;

    private ?MatchGame $matchGame;

    private int $baseEquivalent;

    private int $percentOfBaseEquivalent;

    private int $grossEquivalent;

    private int $taxDeductibleStakePercent;

    private int $taxDeductibleExpenses;

    private int $taxationBase;

    private int $incomeTaxStakePercent;

    private int $incomeTax;

    private int $equivalentToWithdraw;

    public function __construct(
        Person $person,
        MatchGame $matchGame,
        int $baseEquivalent,
        int $percentOfBaseEquivalent,
        int $taxDeductibleStakePercent,
        int $incomeTaxStakePercent,
    ) {
        $this->id                        = MatchGameBillId::generate();
        $this->person                    = $person;
        $this->matchGame                 = $matchGame;
        $this->baseEquivalent            = $baseEquivalent;
        $this->percentOfBaseEquivalent   = $percentOfBaseEquivalent;
        $this->taxDeductibleStakePercent = $taxDeductibleStakePercent;
        $this->incomeTaxStakePercent     = $incomeTaxStakePercent;

        $this->createdAtTraitConstruct();
    }

    public function getId(): MatchGameBillId
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getMatchGame(): ?MatchGame
    {
        return $this->matchGame;
    }

    public function setMatchGame(?MatchGame $matchGame): self
    {
        $this->matchGame = $matchGame;

        return $this;
    }

    public function getBaseEquivalent(): int
    {
        return $this->baseEquivalent;
    }

    public function setBaseEquivalent(int $amount): self
    {
        $this->baseEquivalent = $amount;

        return $this;
    }

    public function getPercentOfBaseEquivalent(): int
    {
        return $this->percentOfBaseEquivalent;
    }

    public function setPercentOfBaseEquivalent(int $percent): self
    {
        $this->percentOfBaseEquivalent = $percent;

        return $this;
    }

    public function getGrossEquivalent(): int
    {
        return $this->grossEquivalent;
    }

    public function setGrossEquivalent(int $amount): self
    {
        $this->grossEquivalent = $amount;

        return $this;
    }

    public function getTaxDeductibleStakePercent(): int
    {
        return $this->taxDeductibleStakePercent;
    }

    public function setTaxDeductibleStakePercent(int $percent): self
    {
        $this->taxDeductibleStakePercent = $percent;

        return $this;
    }

    public function getTaxDeductibleExpenses(): int
    {
        return $this->taxDeductibleExpenses;
    }

    public function setTaxDeductibleExpenses(int $amount): self
    {
        $this->taxDeductibleExpenses = $amount;

        return $this;
    }

    public function getTaxationBase(): int
    {
        return $this->taxationBase;
    }

    public function setTaxationBase(int $amount): self
    {
        $this->taxationBase = $amount;

        return $this;
    }

    public function getIncomeTaxStakePercent(): int
    {
        return $this->incomeTaxStakePercent;
    }

    public function setIncomeTaxStakePercent(int $percent): self
    {
        $this->incomeTaxStakePercent = $percent;

        return $this;
    }

    public function getIncomeTax(): int
    {
        return $this->incomeTax;
    }

    public function setIncomeTax(int $amount): self
    {
        $this->incomeTax = $amount;

        return $this;
    }

    public function getEquivalentToWithdraw(): int
    {
        return $this->equivalentToWithdraw;
    }

    public function setEquivalentToWithdraw(int $amount): self
    {
        $this->equivalentToWithdraw = $amount;

        return $this;
    }

    public function __sleep(): array
    {
        return [];
    }
}
