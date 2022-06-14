<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Entity\MatchGameBill;
use App\Entity\MatchGame;
use App\Security\Voter\MatchGameBillVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillDeleteController extends MatchGameBillAbstractController
{
    #[ParamConverter('matchGame', options: ['mapping' => ['match_game_id' => 'id']])]
    #[ParamConverter('matchGameBill', options: ['mapping' => ['match_game_bill_id' => 'id']])]
    public function delete(MatchGame $matchGame, MatchGameBill $matchGameBill, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::DELETE, $matchGameBill);

        $this->validateCsrf('match_game_bill_delete', $request->request->get('_token'));

        $this->manager->delete($matchGameBill);

        return $this->redirectToSingleMatchGame($matchGame);
    }
}
