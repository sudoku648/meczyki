<?php

declare(strict_types=1);

namespace App\Controller\Person\Delegate;

use App\Controller\Person\PersonAbstractController;
use App\Service\PersonManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class DelegateAbstractController extends PersonAbstractController
{
    protected PersonManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        PersonManager $manager,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        Breadcrumbs $breadcrumbs
    )
    {
        parent::__construct(
            $manager,
            $router,
            $dispatcher,
            $breadcrumbs
        );

        $this->breadcrumbs->addItem(
            'Delegaci',
            $this->router->generate('delegates_front')
        );
    }
}
