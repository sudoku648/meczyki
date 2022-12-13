<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Entity\MatchGame;
use App\Entity\MatchGameBill;
use App\Security\Voter\MatchGameBillVoter;
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