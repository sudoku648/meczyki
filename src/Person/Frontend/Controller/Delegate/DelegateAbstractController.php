<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\Delegate;

use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Person\Frontend\Controller\PersonAbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class DelegateAbstractController extends PersonAbstractController
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
            'Delegaci',
            $this->router->generate('delegates_list')
        );
    }
}
