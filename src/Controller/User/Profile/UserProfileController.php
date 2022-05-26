<?php

declare(strict_types=1);

namespace App\Controller\User\Profile;

use App\Event\User\Profile\UserProfileHasBeenSeenEvent;
use App\Security\Voter\UserProfileVoter;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends UserProfileAbstractController
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(
            UserProfileVoter::PROFILE_SHOW,
            $user = $this->getUserOrThrow()
        );

        $this->dispatcher->dispatch((new UserProfileHasBeenSeenEvent($user)));

        return $this->render(
            'user/profile/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
