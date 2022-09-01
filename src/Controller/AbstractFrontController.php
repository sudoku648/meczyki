<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class AbstractFrontController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs
    ) {
        parent::__construct(
            $router,
            $dispatcher,
            $breadcrumbs
        );
    }
}
