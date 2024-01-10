<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Controller\AbstractController;
use App\Entity\UserRole;
use App\Service\Contracts\UserRoleManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class UserRoleAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected UserRoleManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('dashboard')
        );
        $this->breadcrumbs->addItem(
            'Role użytkowników',
            $this->router->generate('user_roles_list')
        );
    }

    protected function redirectToUserRolesList(): Response
    {
        return $this->redirectToRoute(
            'user_roles_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditUserRole(UserRole $userRole): Response
    {
        return $this->redirectToRoute(
            'user_role_edit',
            [
                'user_role_id' => $userRole->getId(),
            ]
        );
    }
}
