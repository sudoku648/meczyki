<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Form\PersonType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonEditController extends PersonAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        $this->breadcrumbs->addItem(
            'Edytuj osobę',
            $this->router->generate(
                'person_edit',
                [
                    'person_id' => $person->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($person);

        $form = $this->createForm(PersonType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $this->manager->edit($person, $dto);

            $this->addFlash('success', 'Osoba została zaktualizowana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditPerson($person);
            }

            return $this->redirectToRoute(
                'people_list',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'person/edit.html.twig',
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
