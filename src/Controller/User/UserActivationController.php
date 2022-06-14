<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Message\Flash\User\UserActivatedFlashMessage;
use App\Message\Flash\User\UserDeactivatedFlashMessage;
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

        $this->flash(new UserActivatedFlashMessage($user->getId()));

        return $this->redirectToRefererOrHome($request);
    }

    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function deactivate(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DEACTIVATE, $user);

        $this->validateCsrf('user_deactivate', $request->request->get('_token'));

        $this->manager->deactivate($user);

        $this->flash(new UserDeactivatedFlashMessage($user->getId()));

        return $this->redirectToRefererOrHome($request);
    }
}
