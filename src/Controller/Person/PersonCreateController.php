<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Form\PersonType;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonCreateController extends PersonAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj osobę',
            $this->router->generate('person_create')
        );

        $form = $this->createForm(PersonType::class);
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
                'people_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/new.html.twig',
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
