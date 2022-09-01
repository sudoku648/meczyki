<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDeleteController extends UserAbstractController
{
    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function delete(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->validateCsrf('user_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Użytkownik został usunięty.');

        $this->manager->delete($user);

        return $this->redirectToUsersList();
    }

    public function deleteBatch(UserRepository $repository, Request $request): Response
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
