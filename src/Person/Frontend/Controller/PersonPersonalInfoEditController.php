<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Frontend\Form\PersonPersonalInfoType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonPersonalInfoEditController extends PersonAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT_PERSONAL_INFO);

        $this->breadcrumbs->addItem(
            'Edytuj dane osobowe',
            $this->router->generate(
                'person_personal_info_edit',
                []
            )
        );

        $person = $this->getUserOrThrow()->getPerson();

        $dto = $this->manager->createDto($person);

        $form = $this->createForm(PersonPersonalInfoType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $this->manager->editPersonalInfo($person, $dto);

            $this->addFlash('success', 'Dane osobowe zostały zaktualizowane.');

            return $this->redirectToRoute(
                'person_personal_info_edit',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/edit_personal_info.html.twig',
            [
                'form'   => $form->createView(),
                'person' => $person,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}