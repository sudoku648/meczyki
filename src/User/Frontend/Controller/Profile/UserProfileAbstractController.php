<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller\Profile;

use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class UserProfileAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
    ) {
        $this->breadcrumbs->addItem(
            'Profil',
            $this->router->generate('user_profile')
        );
    }
}
