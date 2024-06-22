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

final class UserDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->validateCsrf('user_delete', $request->request->get('_token'));

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'User has been deleted.',
            domain: 'User',
        ));

        $this->manager->delete($user);

        return $this->redirectToUsersList();
    }

    /** @param DoctrineUserRepository $repository */
    public function deleteBatch(UserRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE_BATCH);

        $this->validateCsrf('user_delete_batch', $request->request->get('_token'));

        $userIds = $request->request->all('users');

        $notAllDeleted = false;
        foreach ($userIds as $userId) {
            $user = $repository->find($userId);
            if ($user) {
                if ($this->isGranted(UserVoter::DELETE, $user)) {
                    $this->manager->delete($user);

                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen users have been deleted.',
                domain: 'User',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Users have been deleted.',
                domain: 'User',
            ));
        }

        return $this->redirectToUsersList();
    }
}
