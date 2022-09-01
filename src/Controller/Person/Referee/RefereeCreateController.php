<?php

declare(strict_types=1);

namespace App\Controller\Person\Referee;

use App\Form\RefereeType;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereeCreateController extends RefereeAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj sędziego',
            $this->router->generate('referee_create')
        );

        $form = $this->createForm(RefereeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto            = $form->getData();
            $dto->isReferee = true;

            $this->manager->create($dto);

            $this->addFlash('success', 'Osoba została dodana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToRoute(
                'referees_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/referee/new.html.twig',
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
