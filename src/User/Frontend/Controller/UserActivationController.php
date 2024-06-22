<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserActivationController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserManagerInterface $manager,
    ) {
    }

    public function activate(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::ACTIVATE, $user);

        $this->validateCsrf('user_activate', $request->request->get('_token'));

        $this->manager->activate($user);

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'User has been activated.',
            domain: 'User',
        ));

        return $this->redirectToRefererOrHome($request);
    }

    public function deactivate(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::DEACTIVATE, $user);

        $this->validateCsrf('user_deactivate', $request->request->get('_token'));

        $this->manager->deactivate($user);

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'User has been deactivated.',
            domain: 'User',
        ));

        return $this->redirectToRefererOrHome($request);
    }

    /** @param DoctrineUserRepository $repository */
    public function activateBatch(UserRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ACTIVATE_BATCH);

        $this->validateCsrf('user_activate_batch', $request->request->get('_token'));

        $userIds = $request->request->all('users');

        $notAllActivated = false;
        foreach ($userIds as $userId) {
            $user = $repository->find($userId);
            if ($user) {
                if ($this->isGranted(UserVoter::ACTIVATE, $user)) {
                    $this->manager->activate($user);

                    continue;
                }

                $notAllActivated = true;
            }
        }

        if ($notAllActivated) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen users have been activated.',
                domain: 'User',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Users have been activated.',
                domain: 'User',
            ));
        }

        return $this->redirectToUsersList();
    }

    /** @param DoctrineUserRepository $repository */
    public function deactivateBatch(UserRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DEACTIVATE_BATCH);

        $this->validateCsrf('user_deactivate_batch', $request->request->get('_token'));

        $userIds = $request->request->all('users');

        $notAllDeactivated = false;
        foreach ($userIds as $userId) {
            $user = $repository->find($userId);
            if ($user) {
                if ($this->isGranted(UserVoter::DEACTIVATE, $user)) {
                    $this->manager->deactivate($user);

                    continue;
                }

                $notAllDeactivated = true;
            }
        }

        if ($notAllDeactivated) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen users have been deactivated.',
                domain: 'User',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Users have been deactivated.',
                domain: 'User',
            ));
        }

        return $this->redirectToUsersList();
    }
}
