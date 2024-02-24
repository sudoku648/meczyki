<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class UserAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected Breadcrumbs $breadcrumbs,
        protected UserManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('dashboard')
        );
        $this->breadcrumbs->addItem(
            'Użytkownicy',
            $this->router->generate('users_list')
        );
    }

    protected function redirectToUsersList(): Response
    {
        return $this->redirectToRoute(
            'users_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditUser(User $user): Response
    {
        return $this->redirectToRoute(
            'user_edit',
            [
                'user_id' => $user->getId(),
            ]
        );
    }

    protected function redirectToUserBindWithPerson(User $user): Response
    {
        return $this->redirectToRoute(
            'user_bind_with_person',
            [
                'user_id' => $user->getId(),
            ]
        );
    }
}
