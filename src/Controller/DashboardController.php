<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\Voter\DashboardVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class DashboardController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected Breadcrumbs $breadcrumbs,
    ) {
    }

    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(DashboardVoter::DASHBOARD);

        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('dashboard')
        );

        return $this->render('dashboard/dashboard.html.twig');
    }
}
