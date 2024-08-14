<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Service;

use NumberFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function dirname;
use function in_array;
use function strlen;

final readonly class MatchGameBillXlsxGenerator implements MatchGameBillGeneratorInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function generate(MatchGameBill $matchGameBill): Spreadsheet
    {
        $matchGame = $matchGameBill->getMatchGame();
        $person    = $matchGameBill->getPerson();

        $pathToBaseFile = dirname(__DIR__) . '/../../../templates/match_game_bill/blank-bill.xlsx';

        $reader      = IOFactory::createReaderForFile($pathToBaseFile);
        $spreadsheet = $reader->load($pathToBaseFile);

        $sheet = $spreadsheet->getSheet(0);

        $sheet->setCellValue('A8', $matchGame->getDateTime()->format('d.m.Y'));
        $sheet->setCellValue('U8', $matchGame->getVenue());
        $sheet->setCellValue('BG8', $matchGame->getGameType()?->getName() ?? '');
        $sheet->setCellValue('A11', $matchGame->getHomeTeam()?->getName() ?? '');
        $sheet->setCellValue('BH11', $matchGame->getAwayTeam()?->getName() ?? '');

        $sheet->setCellValue(
            'A14',
            $this->translator->trans(
                id: $matchGameBill->getFunction()->value,
                domain: 'Person',
            )
        );
        $sheet->setCellValue('AK14', $person->getLastName());
        $sheet->setCellValue('CD14', $person->getFirstName());
        $sheet->setCellValue('A17', $person->getDateOfBirth()?->format('d.m.Y') ?? '');
        $sheet->setCellValue('U17', $person->getPlaceOfBirth() ?? '');

        $sheet->setCellValue('BB17', $person->getAddress()->formatted());
        $sheet->setCellValue(
            'A20',
            $person->getAddress()->getVoivodeship()
            ? $this->translator->trans(
                id: $person->getAddress()->getVoivodeship()->getName(),
                domain: 'Voivodeship',
                locale: 'pl',
            )
            : ''
        );
        $sheet->setCellValue('AM20', $person->getAddress()->getCounty() ?? '');
        $sheet->setCellValue('BY20', $person->getAddress()->getGmina() ?? '');

        $taxOffice = '';
        if ($person->getTaxOfficeName() && $person->getTaxOfficeAddress()) {
            $taxOffice = $person->getTaxOfficeName() . ', ' . $person->getTaxOfficeAddress();
        }

        $sheet->setCellValue('A23', $taxOffice);

        $pesel = $person->getPesel()?->getValue();
        if ($pesel) {
            $startCol = 6;
            $colWidth = 4;

            for ($i = 0; $i < strlen($pesel); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $pesel[$i]);
            }
        }

        $nip = $person->getNip()?->getValue();
        if ($nip && !$pesel) {
            $startCol = 70;
            $colWidth = 4;

            for ($i = 0; $i < strlen($nip); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $nip[$i]);
            }
        }

        $sheet->setCellValue('BA32', $matchGame->getDateTime()->format('d.m.Y'));
        $sheet->setCellValue('O39', $person->getFirstName() . ' ' . $person->getLastName());

        $sheet->setCellValue('BQ41', $matchGameBill->getBaseEquivalent()->getAmount());
        $sheet->setCellValue('BQ42', $matchGameBill->getPercentOfBaseEquivalent()->getValue());
        $sheet->setCellValue('BQ43', $matchGameBill->getGrossEquivalent()->getAmount());
        $sheet->setCellValue('BQ44', $matchGameBill->getTaxDeductibleExpenses()->getAmount());
        $sheet->setCellValue('BQ45', $matchGameBill->getTaxationBase()->getAmount());
        $sheet->setCellValue('BQ46', $matchGameBill->getIncomeTax()->getAmount());
        $sheet->setCellValue('BQ48', $matchGameBill->getEquivalentToWithdraw()->getAmount());

        $numberFormatter = new NumberFormatter('pl', NumberFormatter::SPELLOUT);
        $amountInWords   = $numberFormatter->format($matchGameBill->getEquivalentToWithdraw()->getAmount()) . ' zł 00/100';

        $sheet->setCellValue('O49', $amountInWords);

        $iban = $person->getIban()?->getNumber();
        if ($iban) {
            $startCol = 3;
            $colWidth = 4;
            $offset   = 0;

            for ($i = 0; $i < strlen($iban); $i++) {
                if (in_array($i, [2, 6, 10, 14, 18, 22])) {
                    $offset++;
                }

                $col = $startCol + $i * $colWidth + $offset;
                $sheet->setCellValueByColumnAndRow($col, 53, $iban[$i]);
            }
        }

        $richText = new RichText();

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
}
