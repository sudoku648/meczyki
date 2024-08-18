<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Persistence\MatchGameBillRepositoryInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillCalculatorInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillGeneratorInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\BaseEquivalentPercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxIncomeStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;
use Webmozart\Assert\Assert;

readonly class MatchGameBillManager implements MatchGameBillManagerInterface
{
    public function __construct(
        private MatchGameBillRepositoryInterface $repository,
        private MatchGameBillGeneratorInterface $generator,
        private MatchGameBillCalculatorInterface $calculator,
    ) {
    }

    public function create(MatchGameBillDto $dto, Person $person): MatchGameBill
    {
        $matchGameBill = new MatchGameBill(
            $person,
            $dto->matchGame,
            Money::PLN($dto->baseEquivalent),
            BaseEquivalentPercent::byValue($dto->percentOfBaseEquivalent),
            TaxDeductibleStakePercent::byValue($dto->taxDeductibleStakePercent),
            TaxIncomeStakePercent::byValue($dto->incomeTaxStakePercent),
        );

        $matchGameBill = $this->calculateValues($matchGameBill);
        $matchGameBill = $this->resolveFunction($matchGameBill);

        $this->repository->persist($matchGameBill);

        return $matchGameBill;
    }

    public function edit(MatchGameBill $matchGameBill, MatchGameBillDto $dto): MatchGameBill
    {
        Assert::same($matchGameBill->getMatchGame()->getId(), $dto->matchGame->getId());

        $matchGameBill->setBaseEquivalent(Money::PLN($dto->baseEquivalent));
        $matchGameBill->setPercentOfBaseEquivalent(BaseEquivalentPercent::byValue($dto->percentOfBaseEquivalent));
        $matchGameBill->setTaxDeductibleStakePercent(TaxDeductibleStakePercent::byValue($dto->taxDeductibleStakePercent));
        $matchGameBill->setIncomeTaxStakePercent(TaxIncomeStakePercent::byValue($dto->incomeTaxStakePercent));
        $matchGameBill->setUpdatedAt();

        $matchGameBill = $this->calculateValues($matchGameBill);
        $matchGameBill = $this->resolveFunction($matchGameBill);

        $this->repository->persist($matchGameBill);

        return $matchGameBill;
    }

    public function delete(MatchGameBill $matchGameBill): void
    {
        $this->repository->remove($matchGameBill);
    }

    public function generateXlsx(MatchGameBill $matchGameBill): Spreadsheet
    {
        return $this->generator->generate($matchGameBill);
    }

    private function calculateValues(MatchGameBill $matchGameBill): MatchGameBill
    {
        $values = $this->calculator->calculate($matchGameBill);

        $matchGameBill
            ->setGrossEquivalent($values->grossEquivalent)
            ->setTaxDeductibleExpenses($values->taxDeductibleExpenses)
            ->setTaxationBase($values->taxationBase)
            ->setIncomeTax($values->incomeTax)
            ->setEquivalentToWithdraw($values->equivalentToWithdraw)
        ;

        return $matchGameBill;
    }

    private function resolveFunction(MatchGameBill $matchGameBill): MatchGameBill
    {
        $matchGame = $matchGameBill->getMatchGame();

        $function = match ($matchGameBill->getPerson()->getId()) {
            $matchGame->getDelegate()?->getId() => MatchGameFunction::DELEGATE,
            $matchGame->getReferee()->getId(),
            $matchGame->getFirstAssistantReferee()?->getId(),
            $matchGame->getSecondAssistantReferee()?->getId(),
            $matchGame->getFourthOfficial()?->getId()  => MatchGameFunction::REFEREE,
            $matchGame->getRefereeObserver()?->getId() => MatchGameFunction::REFEREE_OBSERVER,
        };

        $matchGameBill->setFunction($function);

        return $matchGameBill;
    }
}
