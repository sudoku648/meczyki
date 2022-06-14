<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\AbstractController;
use App\Entity\User;
use App\Service\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class UserAbstractController extends AbstractController
{
    protected UserManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        UserManager $manager,
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
            'Użytkownicy',
            $this->router->generate('users_front')
        );
    }

    protected function redirectToUsersList(): Response
    {
        return $this->redirectToRoute(
            'users_front',
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
