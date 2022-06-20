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
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

class MatchGameBillManager implements ContentManagerInterface
{
    private EventDispatcherInterface $dispatcher;
    private EntityManagerInterface $entityManager;
    private MatchGameBillFactory $factory;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        MatchGameBillFactory $factory
    )
    {
        $this->dispatcher    = $dispatcher;
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
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

    public function generateXlsx(MatchGameBill $matchGameBill)
    {
        $matchGame = $matchGameBill->getMatchGame();
        $person = $matchGameBill->getPerson();

        $pathToBaseFile = \dirname(__DIR__).'/Resource/blank-bill.xlsx';

        $reader = IOFactory::createReaderForFile($pathToBaseFile);
        $spreadsheet = $reader->load($pathToBaseFile);

        $sheet = $spreadsheet->getSheet(0);

        $sheet->setCellValue('A8', $matchGame->getDateTime()->format('d.m.Y'));
        $sheet->setCellValue('U8', $matchGame->getVenue());
        $sheet->setCellValue('BG8', $matchGame->getGameType() ? $matchGame->getGameType()->getFullName() : '');
        $sheet->setCellValue('A11', $matchGame->getHomeTeam() ? $matchGame->getHomeTeam()->getFullName() : '');
        $sheet->setCellValue('BH11', $matchGame->getAwayTeam() ? $matchGame->getAwayTeam()->getFullName() : '');

        $function = '';
        if ($person->isReferee())             $function = 'sędzia';
        elseif ($person->isRefereeObserver()) $function = 'obserwator';
        elseif ($person->isDelegate())        $function = 'delegat';

        $sheet->setCellValue('A14', $function);
        $sheet->setCellValue('AK14', $person->getLastName());
        $sheet->setCellValue('CD14', $person->getFirstName());
        $sheet->setCellValue('A17', $person->getDateOfBirth() ? $person->getDateOfBirth()->format('d.m.Y') : '');
        $sheet->setCellValue('U17', $person->getPlaceOfBirth() ? $person->getPlaceOfBirth() : '');

        $sheet->setCellValue('BB17', $person->getAddress());
        $sheet->setCellValue('A20', $person->getAddressVoivodeship() ? $person->getAddressVoivodeship() : '');
        $sheet->setCellValue('AM20', $person->getAddressPowiat() ? $person->getAddressPowiat() : '');
        $sheet->setCellValue('BY20', $person->getAddressGmina() ? $person->getAddressGmina() : '');

        $taxOffice = '';
        if ($person->getTaxOfficeName() && $person->getTaxOfficeAddress()) {
            $taxOffice = $person->getTaxOfficeName().', '.$person->getTaxOfficeAddress();
        }

        $sheet->setCellValue('A23', $taxOffice);

        $pesel = $person->getPesel();
        if ($pesel) {
            $startCol = 6;
            $colWidth = 4;

            for ($i = 0; $i < \strlen($pesel); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $pesel[$i]);
            }
        }

        $nip = $person->getNip();
        if ($nip && !$pesel) {
            $startCol = 70;
            $colWidth = 4;

            for ($i = 0; $i < \strlen($nip); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $nip[$i]);
            }
        }

        $sheet->setCellValue('BA32', $matchGame->getDateTime()->format('d.m.Y'));
        $sheet->setCellValue('O39', $person->getFullNameInversed());

        $sheet->setCellValue('BQ41', $matchGameBill->getBaseEquivalent());
        $sheet->setCellValue('BQ42', $matchGameBill->getPercentOfBaseEquivalent());
        $sheet->setCellValue('BQ43', $matchGameBill->getGrossEquivalent());
        $sheet->setCellValue('BQ44', $matchGameBill->getTaxDeductibleExpenses());
        $sheet->setCellValue('BQ45', $matchGameBill->getTaxationBase());
        $sheet->setCellValue('BQ46', $matchGameBill->getIncomeTax());
        $sheet->setCellValue('BQ48', $matchGameBill->getEquivalentToWithdraw());

        $numberFormatter = new \NumberFormatter('pl', \NumberFormatter::SPELLOUT);
        $amountInWords = $numberFormatter->format($matchGameBill->getEquivalentToWithdraw()).' zł 00/100';

        $sheet->setCellValue('O49', $amountInWords);

        $iban = $person->getIban();
        if ($iban) {
            $startCol = 3;
            $colWidth = 4;
            $offset = 0;

            $bankAccountNumber = \substr($iban, 2);

            for ($i = 0; $i < \strlen($bankAccountNumber); $i++) {
                if (\in_array($i, [2, 6, 10, 14, 18, 22])) $offset++;

                $col = $startCol + $i * $colWidth + $offset;
                $sheet->setCellValueByColumnAndRow($col, 53, $bankAccountNumber[$i]);
            }
        }

        $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();

        $email = $person->getEmail();
        if ($email && $person->allowsToSendPitByEmail()) {
            $startCol = 3;
            $colWidth = 4;
            $length = \strlen($email);

            for ($col = $startCol; $col < $startCol + $length * $colWidth; $col = $col + $colWidth) {
                $sheet->setCellValueByColumnAndRow($col, 56, $email[($col - $startCol) / $colWidth]);
            }

            $richText->createTextRun('Wyrażam zgodę/');
            $strikethrough = $richText->createTextRun('nie wyrażam zgody');
            $strikethrough->getFont()->setStrikethrough(true);
        } else {
            $strikethrough = $richText->createTextRun('Wyrażam zgodę');
            $strikethrough->getFont()->setStrikethrough(true);
            $richText->createTextRun('/nie wyrażam zgody');
        }

        $richText->createTextRun('* na wysłanie informacji PIT-11 za obecny rok podatkowy na adres e-mail:');
        foreach ($richText->getRichTextElements() as $el) {
            $el->getFont()->setBold(true)->setItalic(true)->setName('Times New Roman')->setSize(9);
        }

        $sheet->setCellValue('A55', $richText);

        $sheet->setCellValue('BA58', $matchGame->getDateTime()->format('d.m.Y'));

        return $spreadsheet;
    }

    public function createDto(MatchGameBill $matchGameBill): MatchGameBillDto
    {
        return $this->factory->createDto($matchGameBill);
    }

    private function calculateValues(MatchGameBill $matchGameBill): MatchGameBill
    {
        $grossEquivalent = (int) \round($matchGameBill->getBaseEquivalent() * $matchGameBill->getPercentOfBaseEquivalent() / 100, 0);
        $taxDeductibleExpenses = (int) \round($grossEquivalent * $matchGameBill->getTaxDeductibleStakePercent() / 100, 0);
        $taxationBase = $grossEquivalent - $taxDeductibleExpenses;
        $incomeTax = (int) \round($taxationBase * $matchGameBill->getIncomeTaxStakePercent() / 100, 0);
        $equivalentToWithdraw = $grossEquivalent - $incomeTax;

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
