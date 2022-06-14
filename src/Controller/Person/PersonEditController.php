<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
use App\Form\PersonType;
use App\Message\Flash\Person\PersonUpdatedFlashMessage;
use App\Security\Voter\PersonVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonEditController extends PersonAbstractController
{
    #[ParamConverter('person', options: ['mapping' => ['person_id' => 'id']])]
    public function __invoke(Person $person, Request $request): Response
    {
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

            $this->flash(new PersonUpdatedFlashMessage($person->getId()));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditPerson($person);
            }

            return $this->redirectToRoute(
                'people_front',
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
