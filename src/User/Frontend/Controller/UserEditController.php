<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Sudoku648\Meczyki\User\Frontend\Dto\UpdateUserDto;
use Sudoku648\Meczyki\User\Frontend\Factory\UpdateUserDtoFactory;
use Sudoku648\Meczyki\User\Frontend\Form\UserBasicType;
use Sudoku648\Meczyki\User\Frontend\Form\UserPasswordType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserEditController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly UserManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('users_list')
            ->add('user_edit', ['user_id' => $user->getId()]);

        $dto = UpdateUserDtoFactory::fromEntity($user);

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
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }

    private function handleForm(
        FormInterface $form,
        User $user,
        UpdateUserDto $dto,
        UserManagerInterface $manager,
        Request $request,
    ): FormInterface|Response {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->edit($user, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'User has been updated.',
                domain: 'User',
            ));

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
