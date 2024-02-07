<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Sudoku648\Meczyki\User\Frontend\Dto\UserDto;
use Sudoku648\Meczyki\User\Frontend\Form\UserBasicType;
use Sudoku648\Meczyki\User\Frontend\Form\UserPasswordType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserEditController extends UserAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $this->breadcrumbs->addItem(
            'Edytuj użytkownika',
            $this->router->generate(
                'user_edit',
                [
                    'user_id' => $user->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($user);

        $basicForm = $this->handleForm(
            $this->createForm(UserBasicType::class, $dto),
            $user,
            $dto,
            $this->manager,
            $request,
        );
        if (!$basicForm instanceof FormInterface) {
            return $basicForm;
        }

        $passwordForm = $this->handleForm(
            $this->createForm(UserPasswordType::class, $dto),
            $user,
            $dto,
            $this->manager,
            $request,
        );
        if (!$passwordForm instanceof FormInterface) {
            return $passwordForm;
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form_basic'    => $basicForm->createView(),
                'form_password' => $passwordForm->createView(),
                'user'          => $user,
            ],
            new Response(
                null,
                $basicForm->isSubmitted() && !$basicForm->isValid()
                || $passwordForm->isSubmitted() && !$passwordForm->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }

    private function handleForm(
        FormInterface $form,
        User $user,
        UserDto $dto,
        UserManagerInterface $manager,
        Request $request,
    ): FormInterface|Response {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->edit($user, $dto);

            $this->addFlash('success', 'Użytkownik został zaktualizowany.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditUser($user);
            }

            return $this->redirectToUsersList();
        }

        return $form;
    }
}
