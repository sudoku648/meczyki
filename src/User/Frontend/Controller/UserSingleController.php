<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

final class UserSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::SHOW, $user);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('users_list')
            ->add('user_single', ['user_id' => $user->getId()]);

        return $this->render(
            'user/single.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
