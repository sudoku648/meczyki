<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Entity\MatchGame;
use App\Entity\MatchGameBill;
use App\Form\MatchGameBillType;
use App\Security\Voter\MatchGameBillVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillEditController extends MatchGameBillAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::EDIT, $matchGameBill);

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
                'Edytuj rachunek',
                $this->router->generate(
                    'match_game_bill_edit',
                    [
                        'match_game_id'      => $matchGame->getId(),
                        'match_game_bill_id' => $matchGameBill->getId(),
                    ]
                )
            )
        ;

        $dto = $this->manager->createDto($matchGameBill);

        $form = $this->createForm(MatchGameBillType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchGameBill = $this->manager->edit($matchGameBill, $dto);

            $this->addFlash('success', 'Rachunek meczowy został zaktualizowany.');

            return $this->redirectToRoute(
                'match_game_bill_single',
                [
                    'match_game_id'      => $matchGame->getId(),
                    'match_game_bill_id' => $matchGameBill->getId(),
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'match_game_bill/edit.html.twig',
            [
                'form'          => $form->createView(),
                'matchGameBill' => $matchGameBill,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}