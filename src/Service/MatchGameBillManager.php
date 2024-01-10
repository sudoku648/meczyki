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
use App\Service\Contracts\MatchGameBillGeneratorInterface;
use App\Service\Contracts\MatchGameBillManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

use function round;

readonly class MatchGameBillManager implements MatchGameBillManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MatchGameBillFactory $factory,
        private MatchGameBillGeneratorInterface $generator,
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

        $matchGameBill->setBaseEquivalent($dto->baseEquivalent);
        $matchGameBill->setPercentOfBaseEquivalent($dto->percentOfBaseEquivalent);
        $matchGameBill->setTaxDeductibleStakePercent($dto->taxDeductibleStakePercent);
        $matchGameBill->setIncomeTaxStakePercent($dto->incomeTaxStakePercent);
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
        $grossEquivalent       = (int) round($matchGameBill->getBaseEquivalent() * $matchGameBill->getPercentOfBaseEquivalent() / 100, 0);
        $taxDeductibleExpenses = (int) round($grossEquivalent * $matchGameBill->getTaxDeductibleStakePercent() / 100, 0);
        $taxationBase          = $grossEquivalent - $taxDeductibleExpenses;
        $incomeTax             = (int) round($taxationBase * $matchGameBill->getIncomeTaxStakePercent() / 100, 0);
        $equivalentToWithdraw  = $grossEquivalent - $incomeTax;

        $matchGameBill
            ->setGrossEquivalent($grossEquivalent)
            ->setTaxDeductibleExpenses($taxDeductibleExpenses)
            ->setTaxationBase($taxationBase)
            ->setIncomeTax($incomeTax)
            ->setEquivalentToWithdraw($equivalentToWithdraw)
        ;

        return $matchGameBill;
    }
}
