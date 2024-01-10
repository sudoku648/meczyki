<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Form\MatchGameType;
use App\Security\Voter\MatchGameVoter;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameCreateController extends MatchGameAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj mecz',
            $this->router->generate('match_game_create')
        );

        $form = $this->createForm(MatchGameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto, $this->getUserOrThrow());

            $this->addFlash('success', 'Mecz został dodany.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToMatchGamesList();
        }

        return $this->render(
            'match_game/new.html.twig',
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
