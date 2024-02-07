<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ClubAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected ClubManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('dashboard')
        );
        $this->breadcrumbs->addItem(
            'Kluby',
            $this->router->generate('clubs_list')
        );
    }

    protected function redirectToClubsList(): Response
    {
        return $this->redirectToRoute(
            'clubs_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditClub(Club $club): Response
    {
        return $this->redirectToRoute(
            'club_edit',
            [
                'club_id' => $club->getId(),
            ]
        );
    }
}
