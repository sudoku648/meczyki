<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Entity\MatchGame;
use App\Form\MatchGameType;
use App\Security\Voter\MatchGameVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameEditController extends MatchGameAbstractController
{
    #[ParamConverter('matchGame', options: ['mapping' => ['match_game_id' => 'id']])]
    public function __invoke(MatchGame $matchGame, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::EDIT, $matchGame);

        $this->breadcrumbs->addItem(
            'Edytuj mecz',
            $this->router->generate(
                'match_game_edit',
                [
                    'match_game_id' => $matchGame->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($matchGame);

        $form = $this->createForm(MatchGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchGame = $this->manager->edit($matchGame, $dto);

            $this->addFlash('success', 'Mecz został zaktualizowany.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditMatchGame($matchGame);
            }

            return $this->redirectToMatchGamesList();
        }

        return $this->render(
            'match_game/edit.html.twig',
            [
                'form'      => $form->createView(),
                'matchGame' => $matchGame,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
