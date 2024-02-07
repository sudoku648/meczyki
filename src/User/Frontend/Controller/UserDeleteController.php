<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDeleteController extends UserAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->validateCsrf('user_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Użytkownik został usunięty.');

        $this->manager->delete($user);

        return $this->redirectToUsersList();
    }

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
            $this->addFlash('warning', 'Nie wszyscy użytkownicy zostali usunięci.');
        } else {
            $this->addFlash('success', 'Użytkownicy zostali usunięci.');
        }

        return $this->redirectToUsersList();
    }
}
