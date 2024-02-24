<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller\Bill;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillDeleteController extends AbstractController
{
    public function __construct(
        private readonly MatchGameBillManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::DELETE, $matchGameBill);

        $this->validateCsrf('match_game_bill_delete', $request->request->get('_token'));

        $this->manager->delete($matchGameBill);

        $this->addFlash('success', 'Rachunek meczowy został usunięty.');

        return $this->redirectToSingleMatchGame($matchGame);
    }
}
