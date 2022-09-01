<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserActivationController extends UserAbstractController
{
    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function activate(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ACTIVATE, $user);

        $this->validateCsrf('user_activate', $request->request->get('_token'));

        $this->manager->activate($user);

        $this->addFlash('success', 'Użytkownik został aktywowany.');

        return $this->redirectToRefererOrHome($request);
    }

    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function deactivate(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DEACTIVATE, $user);

        $this->validateCsrf('user_deactivate', $request->request->get('_token'));

        $this->manager->deactivate($user);

        $this->addFlash('success', 'Użytkownik został dezaktywowany.');

        return $this->redirectToRefererOrHome($request);
    }

    public function activateBatch(UserRepository $repository, Request $request): Response
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
            $this->addFlash('warning', 'Nie wszyscy użytkownicy zostali aktywowani.');
        } else {
            $this->addFlash('success', 'Użytkownicy zostali aktywowani.');
        }

        return $this->redirectToUsersList();
    }

    public function deactivateBatch(UserRepository $repository, Request $request): Response
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
            $this->addFlash('warning', 'Nie wszyscy użytkownicy zostali dezaktywowani.');
        } else {
            $this->addFlash('success', 'Użytkownicy zostali dezaktywowani.');
        }

        return $this->redirectToUsersList();
    }
}
