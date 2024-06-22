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
use Sudoku648\Meczyki\User\Frontend\Form\UserBindWithPersonType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserBindWithPersonController extends AbstractController
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
        $this->denyAccessUnlessGranted(UserVoter::BIND_WITH_PERSON, $user);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('users_list')
            ->add('user_single', ['user_id' => $user->getId()])
            ->add('user_bind_with_person', ['user_id' => $user->getId()]);

        $dto = $this->manager->createDto($user);

        $form = $this->createForm(UserBindWithPersonType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($dto->person) {
                $this->manager->bindWithPerson($user, $dto->person);
                $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                    id: 'Person has been bound to user.',
                    domain: 'User',
                ));
            } else {
                $this->manager->unbindPerson($user);
                $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                    id: 'Person has been unbound from user.',
                    domain: 'User',
                ));
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
