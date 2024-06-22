<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller\Profile;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserProfileVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\Response;

final class UserProfileController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(
            UserProfileVoter::PROFILE_SHOW,
            $user = $this->getUserOrThrow(),
        );

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('user_profile');

        return $this->render(
            'user/profile/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
