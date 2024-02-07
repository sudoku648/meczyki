<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillDeleteController extends MatchGameBillAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::DELETE, $matchGameBill);

        $this->validateCsrf('match_game_bill_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Rachunek meczowy został usunięty.');

        $this->manager->delete($matchGameBill);

        return $this->redirectToSingleMatchGame($matchGame);
    }
}
