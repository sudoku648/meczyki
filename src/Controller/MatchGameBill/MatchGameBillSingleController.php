<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Entity\MatchGame;
use App\Entity\MatchGameBill;
use App\Event\MatchGameBill\MatchGameBillHasBeenSeenEvent;
use App\Security\Voter\MatchGameBillVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillSingleController extends MatchGameBillAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::SHOW, $matchGameBill);

        $this->breadcrumbs
            ->addItem(
                $matchGame->getCompetitors(),
                $this->router->generate(
                    'match_game_single',
                    [
                        'match_game_id' => $matchGame->getId(),
                    ]
                ),
                [],
                false
            )
            ->addItem(
                'Rachunek',
                $this->router->generate(
                    'match_game_bill_single',
                    [
                        'match_game_id'      => $matchGame->getId(),
                        'match_game_bill_id' => $matchGameBill->getId(),
                    ]
                )
            )
        ;

        $this->dispatcher->dispatch((new MatchGameBillHasBeenSeenEvent($matchGameBill)));

        return $this->render(
            'match_game_bill/single.html.twig',
            [
                'matchGameBill' => $matchGameBill,
            ]
        );
    }
}