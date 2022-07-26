<?php

declare(strict_types=1);

namespace App\Controller\Person\Delegate;

use App\Form\DelegateType;
use App\Message\Flash\Person\PersonCreatedFlashMessage;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DelegateCreateController extends DelegateAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj delegata',
            $this->router->generate('delegate_create')
        );

        $form = $this->createForm(DelegateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            $dto->isDelegate = true;

            $person = $this->manager->create($dto);

            $this->flash(new PersonCreatedFlashMessage($person->getId()));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToRoute(
                'delegates_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/delegate/new.html.twig',
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
