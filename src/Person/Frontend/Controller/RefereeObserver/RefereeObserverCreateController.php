<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\RefereeObserver;

use Sudoku648\Meczyki\Person\Frontend\Form\RefereeObserverType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereeObserverCreateController extends RefereeObserverAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj obserwatora',
            $this->router->generate('referee_observer_create')
        );

        $form = $this->createForm(RefereeObserverType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto                    = $form->getData();
            $dto->isRefereeObserver = true;

            $this->manager->create($dto);

            $this->addFlash('success', 'Osoba została dodana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToRoute(
                'referee_observers_list',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/referee_observer/new.html.twig',
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