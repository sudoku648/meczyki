<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Form\GameTypeType;
use App\Security\Voter\GameTypeVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeCreateController extends GameTypeAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj typ rozgrywek',
            $this->router->generate('game_type_create')
        );

        $form = $this->createForm(GameTypeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto);

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToRoute(
                'game_types_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'game_type/new.html.twig',
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