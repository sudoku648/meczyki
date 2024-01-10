<?php

declare(strict_types=1);

namespace App\Controller\Person\Referee;

use App\Controller\Person\PersonAbstractController;
use App\Service\Contracts\PersonManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class RefereeAbstractController extends PersonAbstractController
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
            'Sędziowie',
            $this->router->generate('referees_list')
        );
    }
}
