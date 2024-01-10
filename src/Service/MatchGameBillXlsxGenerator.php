<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MatchGameBill;
use App\Service\Contracts\MatchGameBillGeneratorInterface;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use function dirname;
use function in_array;
use function round;
use function strlen;
use function substr;

readonly class MatchGameBillXlsxGenerator implements MatchGameBillGeneratorInterface
{
    public function generate(MatchGameBill $matchGameBill): Spreadsheet
    {
        $matchGame = $matchGameBill->getMatchGame();
        $person    = $matchGameBill->getPerson();

        $pathToBaseFile = dirname(__DIR__) . '/Resource/blank-bill.xlsx';

        $reader      = IOFactory::createReaderForFile($pathToBaseFile);
        $spreadsheet = $reader->load($pathToBaseFile);

        $sheet = $spreadsheet->getSheet(0);

        $sheet->setCellValue('A8', $matchGame->getDateTime()->format('d.m.Y'));
        $sheet->setCellValue('U8', $matchGame->getVenue());
        $sheet->setCellValue('BG8', $matchGame->getGameType() ? $matchGame->getGameType()->getFullName() : '');
        $sheet->setCellValue('A11', $matchGame->getHomeTeam() ? $matchGame->getHomeTeam()->getFullName() : '');
        $sheet->setCellValue('BH11', $matchGame->getAwayTeam() ? $matchGame->getAwayTeam()->getFullName() : '');

        $function = '';
        if ($person->isReferee()) {
            $function = 'sędzia';
        } elseif ($person->isRefereeObserver()) {
            $function = 'obserwator';
        } elseif ($person->isDelegate()) {
            $function = 'delegat';
        }

        $sheet->setCellValue('A14', $function);
        $sheet->setCellValue('AK14', $person->getLastName());
        $sheet->setCellValue('CD14', $person->getFirstName());
        $sheet->setCellValue('A17', $person->getDateOfBirth() ? $person->getDateOfBirth()->format('d.m.Y') : '');
        $sheet->setCellValue('U17', $person->getPlaceOfBirth() ? $person->getPlaceOfBirth() : '');

        $sheet->setCellValue('BB17', $person->getAddress());
        $sheet->setCellValue('A20', $person->getAddressVoivodeship() ? $person->getAddressVoivodeship()->getName() : '');
        $sheet->setCellValue('AM20', $person->getAddressPowiat() ? $person->getAddressPowiat() : '');
        $sheet->setCellValue('BY20', $person->getAddressGmina() ? $person->getAddressGmina() : '');

        $taxOffice = '';
        if ($person->getTaxOfficeName() && $person->getTaxOfficeAddress()) {
            $taxOffice = $person->getTaxOfficeName() . ', ' . $person->getTaxOfficeAddress();
        }

        $sheet->setCellValue('A23', $taxOffice);

        $pesel = $person->getPesel();
        if ($pesel) {
            $startCol = 6;
            $colWidth = 4;

            for ($i = 0; $i < strlen($pesel); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $pesel[$i]);
            }
        }

        $nip = $person->getNip();
        if ($nip && !$pesel) {
            $startCol = 70;
            $colWidth = 4;

            for ($i = 0; $i < strlen($nip); $i++) {
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

        $numberFormatter = new NumberFormatter('pl', NumberFormatter::SPELLOUT);
        $amountInWords   = $numberFormatter->format($matchGameBill->getEquivalentToWithdraw()) . ' zł 00/100';

        $sheet->setCellValue('O49', $amountInWords);

        $iban = $person->getIban();
        if ($iban) {
            $startCol = 3;
            $colWidth = 4;
            $offset   = 0;

            $bankAccountNumber = substr($iban, 2);

            for ($i = 0; $i < strlen($bankAccountNumber); $i++) {
                if (in_array($i, [2, 6, 10, 14, 18, 22])) {
                    $offset++;
                }

                $col = $startCol + $i * $colWidth + $offset;
                $sheet->setCellValueByColumnAndRow($col, 53, $bankAccountNumber[$i]);
            }
        }

        $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();

        $email = $person->getEmail();
        if ($email && $person->allowsToSendPitByEmail()) {
            $startCol = 3;
            $colWidth = 3;
            $length   = strlen($email);

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
