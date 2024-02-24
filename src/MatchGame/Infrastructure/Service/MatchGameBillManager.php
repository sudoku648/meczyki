<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameBillRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameBillCalculatorInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameBillGeneratorInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\BaseEquivalentPercent;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\TaxIncomeStakePercent;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\MatchGame\Frontend\Factory\MatchGameBillFactory;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;
use Webmozart\Assert\Assert;

readonly class MatchGameBillManager implements MatchGameBillManagerInterface
{
    public function __construct(
        private MatchGameBillFactory $factory,
        private MatchGameBillRepositoryInterface $repository,
        private MatchGameBillGeneratorInterface $generator,
        private MatchGameBillCalculatorInterface $calculator,
    ) {
    }

    public function create(MatchGameBillDto $dto, Person $person): MatchGameBill
    {
        $matchGameBill = $this->factory->createFromDto($dto, $person);

        $matchGameBill = $this->calculateValues($matchGameBill);

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

    public function createDto(MatchGameBill $matchGameBill): MatchGameBillDto
    {
        return $this->factory->createDto($matchGameBill);
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
}
