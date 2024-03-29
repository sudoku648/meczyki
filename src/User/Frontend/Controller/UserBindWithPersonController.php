<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Frontend\Form\UserBindWithPersonType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserBindWithPersonController extends UserAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::BIND_WITH_PERSON, $user);

        $this->breadcrumbs
            ->addItem(
                $user->getUsername(),
                $this->router->generate(
                    'user_single',
                    [
                        'user_id' => $user->getId(),
                    ]
                ),
                [],
                false
            )
            ->addItem(
                'Połącz z osobą',
                $this->router->generate(
                    'user_bind_with_person',
                    [
                        'user_id' => $user->getId(),
                    ]
                )
            )
        ;

        $dto = $this->manager->createDto($user);

        $form = $this->createForm(UserBindWithPersonType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($dto->person) {
                $this->manager->bindWithPerson($user, $dto->person);
                $this->addFlash('success', 'Osoba została przypięta do użytkownika.');
            } else {
                $this->manager->unbindPerson($user);
                $this->addFlash('success', 'Osoba została odpięta od użytkownika.');
            }

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToUserBindWithPerson($user);
            }

            return $this->redirectToUsersList();
        }

        return $this->render(
            'user/bind_with_person.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
