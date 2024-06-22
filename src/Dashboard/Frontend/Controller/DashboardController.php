<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Dashboard\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\DashboardVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted(DashboardVoter::DASHBOARD);

        $this->breadcrumbBuilder
            ->add('dashboard');

        return $this->render('dashboard/dashboard.html.twig');
    }
}
