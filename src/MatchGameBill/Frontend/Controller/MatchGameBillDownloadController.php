<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\String\Slugger\SluggerInterface;

use function sys_get_temp_dir;
use function tempnam;

class MatchGameBillDownloadController extends MatchGameBillAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        SluggerInterface $slugger,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::DOWNLOAD, $matchGameBill);

        $spreadsheet = $this->manager->generateXlsx($matchGameBill);

        $fileName = $slugger->slug($matchGame->getCompetitors())->lower()->toString();
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer = new Xlsx($spreadsheet);

        $writer->save($tempFile);

        return $this->file($tempFile, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
