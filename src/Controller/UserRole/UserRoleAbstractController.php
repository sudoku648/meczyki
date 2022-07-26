<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Controller\AbstractController;
use App\Entity\UserRole;
use App\Service\UserRoleManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class UserRoleAbstractController extends AbstractController
{
    protected UserRoleManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        UserRoleManager $manager,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        Breadcrumbs $breadcrumbs
    )
    {
        parent::__construct(
            $router,
            $dispatcher,
            $breadcrumbs
        );

        $this->manager = $manager;

        $this->breadcrumbs->addItem(
            'Role użytkowników',
            $this->router->generate('user_roles_front')
        );
    }

    protected function redirectToUserRolesList(): Response
    {
        return $this->redirectToRoute(
            'user_roles_front',
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
