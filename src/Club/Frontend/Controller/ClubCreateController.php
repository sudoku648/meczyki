<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Frontend\Form\ClubType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubCreateController extends ClubAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj klub',
            $this->router->generate('club_create')
        );

        $form = $this->createForm(ClubType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto);

            $this->addFlash('success', 'Klub został dodany.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToClubsList();
        }

        return $this->render(
            'club/new.html.twig',
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
