<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\MatchGameBillRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchGameBillRepository::class)]
class MatchGameBill
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'matchGameBills')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private ?Person $person;

    #[ORM\ManyToOne(targetEntity: MatchGame::class, inversedBy: 'matchGameBills')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private ?MatchGame $matchGame;

    #[ORM\Column(type: Types::INTEGER)]
    private int $baseEquivalent;

    #[ORM\Column(type: Types::INTEGER)]
    private int $percentOfBaseEquivalent;

    #[ORM\Column(type: Types::INTEGER)]
    private int $grossEquivalent;

    #[ORM\Column(type: Types::INTEGER)]
    private int $taxDeductibleStakePercent;

    #[ORM\Column(type: Types::INTEGER)]
    private int $taxDeductibleExpenses;

    #[ORM\Column(type: Types::INTEGER)]
    private int $taxationBase;

    #[ORM\Column(type: Types::INTEGER)]
    private int $incomeTaxStakePercent;

    #[ORM\Column(type: Types::INTEGER)]
    private int $incomeTax;

    #[ORM\Column(type: Types::INTEGER)]
    private int $equivalentToWithdraw;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    public function __construct(
        Person $person,
        MatchGame $matchGame,
        int $baseEquivalent,
        int $percentOfBaseEquivalent,
        int $taxDeductibleStakePercent,
        int $incomeTaxStakePercent
    ) {
        $this->person                    = $person;
        $this->matchGame                 = $matchGame;
        $this->baseEquivalent            = $baseEquivalent;
        $this->percentOfBaseEquivalent   = $percentOfBaseEquivalent;
        $this->taxDeductibleStakePercent = $taxDeductibleStakePercent;
        $this->incomeTaxStakePercent     = $incomeTaxStakePercent;

        $this->createdAtTraitConstruct();
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

    public function getId(): string
    {
        return $this->id;
    }

    public function __sleep(): array
    {
        return [];
    }
}
