<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillCreatedEvent;
use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillDeletedEvent;
use Sudoku648\Meczyki\MatchGameBill\Domain\Event\MatchGameBillUpdatedEvent;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillCalculatorInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillGeneratorInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\BaseEquivalentPercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\TaxIncomeStakePercent;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Factory\MatchGameBillFactory;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Money;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

readonly class MatchGameBillManager implements MatchGameBillManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MatchGameBillFactory $factory,
        private MatchGameBillGeneratorInterface $generator,
        private MatchGameBillCalculatorInterface $calculator,
    ) {
    }

    public function create(MatchGameBillDto $dto, Person $person): MatchGameBill
    {
        $matchGameBill = $this->factory->createFromDto($dto, $person);

        $matchGameBill = $this->calculateValues($matchGameBill);

        $this->entityManager->persist($matchGameBill);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new MatchGameBillCreatedEvent($matchGameBill));

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

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new MatchGameBillUpdatedEvent($matchGameBill));

        return $matchGameBill;
    }

    public function delete(MatchGameBill $matchGameBill): void
    {
        $this->dispatcher->dispatch(new MatchGameBillDeletedEvent($matchGameBill));

        $this->entityManager->remove($matchGameBill);
        $this->entityManager->flush();
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
