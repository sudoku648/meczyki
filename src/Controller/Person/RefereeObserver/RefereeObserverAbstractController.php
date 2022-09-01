<?php

declare(strict_types=1);

namespace App\Controller\Person\RefereeObserver;

use App\Controller\Person\PersonAbstractController;
use App\Service\PersonManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class RefereeObserverAbstractController extends PersonAbstractController
{
    public function __construct(
        protected PersonManager $manager,
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs
    ) {
        parent::__construct(
            $manager,
            $router,
            $dispatcher,
            $breadcrumbs
        );

        $this->breadcrumbs->addItem(
            'Obserwatorzy',
            $this->router->generate('referee_observers_front')
        );
    }
}
