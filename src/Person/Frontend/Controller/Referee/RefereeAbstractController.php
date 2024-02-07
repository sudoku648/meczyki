<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\Referee;

use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonAbstractController;
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
            'Panel',
            $this->router->generate('dashboard')
        );
        $this->breadcrumbs->addItem(
            'Sędziowie',
            $this->router->generate('referees_list')
        );
    }
}
