<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller\Profile;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserProfileVoter;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends UserProfileAbstractController
{
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(
            UserProfileVoter::PROFILE_SHOW,
            $user = $this->getUserOrThrow()
        );

        return $this->render(
            'user/profile/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
