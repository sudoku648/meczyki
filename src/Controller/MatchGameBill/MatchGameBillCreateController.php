<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Entity\MatchGame;
use App\Form\MatchGameBillType;
use App\Security\Voter\MatchGameVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameBillCreateController extends MatchGameBillAbstractController
{
    #[ParamConverter('matchGame', options: ['mapping' => ['match_game_id' => 'id']])]
    public function __invoke(MatchGame $matchGame, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::CREATE_BILL, $matchGame);

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
                'Dodaj rachunek',
                $this->router->generate(
                    'match_game_bill_create',
                    [
                        'match_game_id' => $matchGame->getId(),
                    ]
                )
            )
        ;

        $form = $this->createForm(MatchGameBillType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto            = $form->getData();
            $dto->matchGame = $matchGame;

            $this->manager->create($dto, $this->getUserOrThrow()->getPerson());

            return $this->redirectToRoute(
                'match_games_single',
                [
                    'matchGame' => $matchGame,
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'match_game_bill/new.html.twig',
            [
                'form' => $form->createView(),
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}