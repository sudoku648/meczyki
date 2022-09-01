<?php

declare(strict_types=1);

namespace App\Controller\Person\Referee;

use App\Controller\Person\PersonAbstractController;
use App\Service\PersonManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class RefereeAbstractController extends PersonAbstractController
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
            'Sędziowie',
            $this->router->generate('referees_front')
        );
    }
}
