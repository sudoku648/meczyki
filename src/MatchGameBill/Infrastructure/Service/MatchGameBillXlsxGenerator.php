<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\MatchGameBillXlsxData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillGeneratorInterface;

use function in_array;
use function strlen;

final readonly class MatchGameBillXlsxGenerator implements MatchGameBillGeneratorInterface
{
    private const string TEMPLATE_PATH = '/templates/match_game_bill/blank-bill.xlsx';

    public function __construct(private string $projectRootDir)
    {
    }

    public function generate(MatchGameBillXlsxData $data): Spreadsheet
    {
        $pathToBaseFile = $this->projectRootDir . self::TEMPLATE_PATH;

        $reader      = IOFactory::createReaderForFile($pathToBaseFile);
        $spreadsheet = $reader->load($pathToBaseFile);

        $sheet = $spreadsheet->getSheet(0);

        $sheet->setCellValue('A8', $data->matchGame->date);
        $sheet->setCellValue('U8', $data->matchGame->venue);
        $sheet->setCellValue('BG8', $data->matchGame->gameTypeName);
        $sheet->setCellValue('A11', $data->matchGame->homeTeamName);
        $sheet->setCellValue('BH11', $data->matchGame->awayTeamName);

        $sheet->setCellValue('A14', $data->person->function);
        $sheet->setCellValue('AK14', $data->person->lastName);
        $sheet->setCellValue('CD14', $data->person->firstName);
        $sheet->setCellValue('A17', $data->person->dateOfBirth);
        $sheet->setCellValue('U17', $data->person->placeOfBirth);

        $sheet->setCellValue('BB17', $data->person->address);
        $sheet->setCellValue('A20', $data->person->voivodeship);
        $sheet->setCellValue('AM20', $data->person->county);
        $sheet->setCellValue('BY20', $data->person->gmina);

        $sheet->setCellValue('A23', $data->person->taxOffice);

        if ($data->person->isPesel) {
            $startCol = 6;
            $colWidth = 4;

            for ($i = 0; $i < strlen($data->person->peselOrNip); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $data->person->peselOrNip[$i]);
            }
        } elseif ('' !== $data->person->peselOrNip) {
            $startCol = 70;
            $colWidth = 4;

            for ($i = 0; $i < strlen($data->person->peselOrNip); $i++) {
                $col = $startCol + $i * $colWidth;
                $sheet->setCellValueByColumnAndRow($col, 26, $data->person->peselOrNip[$i]);
            }
        }

        $sheet->setCellValue('BA32', $data->matchGame->date);
        $sheet->setCellValue('O39', "{$data->person->firstName} {$data->person->lastName}");

        $sheet->setCellValue('BQ41', $data->calculations->baseEquivalent);
        $sheet->setCellValue('BQ42', $data->calculations->percentOfBaseEquivalent);
        $sheet->setCellValue('BQ43', $data->calculations->grossEquivalent);
        $sheet->setCellValue('BQ44', $data->calculations->taxDeductibleExpenses);
        $sheet->setCellValue('BQ45', $data->calculations->taxationBase);
        $sheet->setCellValue('BQ46', $data->calculations->incomeTax);
        $sheet->setCellValue('BQ48', $data->calculations->equivalentToWithdraw);

        $sheet->setCellValue('O49', $data->calculations->amountInWords);

        if ('' !== $data->iban) {
            $startCol = 3;
            $colWidth = 4;
            $offset   = 0;

            for ($i = 0; $i < strlen($data->iban); $i++) {
                if (in_array($i, [2, 6, 10, 14, 18, 22])) {
                    $offset++;
                }

                $col = $startCol + $i * $colWidth + $offset;
                $sheet->setCellValueByColumnAndRow($col, 53, $data->iban[$i]);
            }
        }

        $richText = new RichText();

        if ('' !== $data->email && $data->allowsToSendPitByEmail) {
            $startCol = 3;
            $colWidth = 3;
            $length   = strlen($data->email);

            for ($col = $startCol; $col < $startCol + $length * $colWidth; $col = $col + $colWidth) {
                $sheet->setCellValueByColumnAndRow($col, 56, $data->email[($col - $startCol) / $colWidth]);
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

        $sheet->setCellValue('BA58', $data->matchGame->date);

        return $spreadsheet;
    }
}
