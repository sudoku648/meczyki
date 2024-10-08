<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Entity;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\BaseEquivalentPercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\MatchGameBillId;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxIncomeStakePercent;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Shared\Domain\Entity\CreatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\Entity\UpdatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;

class MatchGameBill
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private MatchGameBillId $id;
    private ?Person $person;
    private ?MatchGame $matchGame;
    private MatchGameFunction $function;
    private Money $baseEquivalent;
    private BaseEquivalentPercent $percentOfBaseEquivalent;
    private Money $grossEquivalent;
    private TaxDeductibleStakePercent $taxDeductibleStakePercent;
    private Money $taxDeductibleExpenses;
    private Money $taxationBase;
    private TaxIncomeStakePercent $incomeTaxStakePercent;
    private Money $incomeTax;
    private Money $equivalentToWithdraw;

    public function __construct(
        Person $person,
        MatchGame $matchGame,
        MatchGameFunction $matchGameFunction,
        Money $baseEquivalent,
        BaseEquivalentPercent $percentOfBaseEquivalent,
        TaxDeductibleStakePercent $taxDeductibleStakePercent,
        TaxIncomeStakePercent $incomeTaxStakePercent,
    ) {
        $this->id                        = MatchGameBillId::generate();
        $this->person                    = $person;
        $this->matchGame                 = $matchGame;
        $this->matchGameFunction         = $matchGameFunction;
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

    public function getFunction(): MatchGameFunction
    {
        return $this->function;
    }

    public function setFunction(MatchGameFunction $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getBaseEquivalent(): Money
    {
        return $this->baseEquivalent;
    }

    public function setBaseEquivalent(Money $money): self
    {
        $this->baseEquivalent = $money;

        return $this;
    }

    public function getPercentOfBaseEquivalent(): BaseEquivalentPercent
    {
        return $this->percentOfBaseEquivalent;
    }

    public function setPercentOfBaseEquivalent(BaseEquivalentPercent $percent): self
    {
        $this->percentOfBaseEquivalent = $percent;

        return $this;
    }

    public function getGrossEquivalent(): Money
    {
        return $this->grossEquivalent;
    }

    public function setGrossEquivalent(Money $money): self
    {
        $this->grossEquivalent = $money;

        return $this;
    }

    public function getTaxDeductibleStakePercent(): TaxDeductibleStakePercent
    {
        return $this->taxDeductibleStakePercent;
    }

    public function setTaxDeductibleStakePercent(TaxDeductibleStakePercent $percent): self
    {
        $this->taxDeductibleStakePercent = $percent;

        return $this;
    }

    public function getTaxDeductibleExpenses(): Money
    {
        return $this->taxDeductibleExpenses;
    }

    public function setTaxDeductibleExpenses(Money $money): self
    {
        $this->taxDeductibleExpenses = $money;

        return $this;
    }

    public function getTaxationBase(): Money
    {
        return $this->taxationBase;
    }

    public function setTaxationBase(Money $money): self
    {
        $this->taxationBase = $money;

        return $this;
    }

    public function getIncomeTaxStakePercent(): TaxIncomeStakePercent
    {
        return $this->incomeTaxStakePercent;
    }

    public function setIncomeTaxStakePercent(TaxIncomeStakePercent $percent): self
    {
        $this->incomeTaxStakePercent = $percent;

        return $this;
    }

    public function getIncomeTax(): Money
    {
        return $this->incomeTax;
    }

    public function setIncomeTax(Money $money): self
    {
        $this->incomeTax = $money;

        return $this;
    }

    public function getEquivalentToWithdraw(): Money
    {
        return $this->equivalentToWithdraw;
    }

    public function setEquivalentToWithdraw(Money $money): self
    {
        $this->equivalentToWithdraw = $money;

        return $this;
    }
}
