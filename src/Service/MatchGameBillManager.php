<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\MatchGameBillDto;
use App\Entity\MatchGameBill;
use App\Entity\Person;
use App\Event\MatchGameBill\MatchGameBillCreatedEvent;
use App\Event\MatchGameBill\MatchGameBillDeletedEvent;
use App\Event\MatchGameBill\MatchGameBillUpdatedEvent;
use App\Factory\MatchGameBillFactory;
use App\Service\Contracts\MatchGameBillCalculatorInterface;
use App\Service\Contracts\MatchGameBillGeneratorInterface;
use App\Service\Contracts\MatchGameBillManagerInterface;
use App\ValueObject\BaseEquivalentPercent;
use App\ValueObject\Money;
use App\ValueObject\TaxDeductibleStakePercent;
use App\ValueObject\TaxIncomeStakePercent;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
