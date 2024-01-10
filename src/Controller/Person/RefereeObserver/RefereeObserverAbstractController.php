<?php

declare(strict_types=1);

namespace App\Controller\Person\RefereeObserver;

use App\Controller\Person\PersonAbstractController;
use App\Service\Contracts\PersonManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class RefereeObserverAbstractController extends PersonAbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected PersonManagerInterface $manager,
    ) {
        parent::__construct(
            $router,
            $dispatcher,
            $breadcrumbs,
            $manager,
        );

        $this->breadcrumbs->addItem(
            'Obserwatorzy',
            $this->router->generate('referee_observers_list')
        );
    }
}
